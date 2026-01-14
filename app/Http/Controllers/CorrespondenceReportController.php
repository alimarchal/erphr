<?php

namespace App\Http\Controllers;

use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\Division;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CorrespondenceReportController extends Controller
{
    /**
     * Display report selection page.
     */
    public function index()
    {
        $stats = [
            'total_receipts' => Correspondence::where('type', 'Receipt')->count(),
            'total_dispatches' => Correspondence::where('type', 'Dispatch')->count(),
            'overdue_receipts' => Correspondence::where('type', 'Receipt')->overdue()->count(),
            'pending_receipts' => Correspondence::where('type', 'Receipt')
                ->whereHas('status', fn ($q) => $q->where('is_final', false))
                ->count(),
        ];

        return view('correspondence.reports.index', compact('stats'));
    }

    /**
     * Generate Receipt Report.
     */
    public function receiptReport(Request $request)
    {
        // Set default date range if not present
        if (!$request->has('filter.received_from')) {
            $request->merge([
                'filter' => array_merge($request->get('filter', []), [
                    'received_from' => now()->startOfMonth()->format('Y-m-d'),
                    'received_to' => now()->format('Y-m-d'),
                ])
            ]);
        }

        $user = auth()->user();

        $query = QueryBuilder::for(Correspondence::query())
            ->allowedFilters([
                AllowedFilter::partial('receipt_no'),
                AllowedFilter::partial('register_number'),
                AllowedFilter::partial('subject'),
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('priority_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('letter_type_id'),
                AllowedFilter::exact('addressed_to_user_id'),
                AllowedFilter::exact('current_holder_id'),
                AllowedFilter::exact('marked_to_user_id'),
                AllowedFilter::exact('to_division_id'),
                AllowedFilter::callback('received_from', fn ($query, $value) => $value ? $query->whereDate('received_date', '>=', $value) : null),
                AllowedFilter::callback('received_to', fn ($query, $value) => $value ? $query->whereDate('received_date', '<=', $value) : null),
                AllowedFilter::callback('overdue', fn ($query, $value) => $value ? $query->overdue() : null),
                AllowedFilter::callback('pending', fn ($query, $value) => $value ? $query->whereHas('status', fn ($q) => $q->where('is_final', false)) : null),
            ])
            ->where('type', 'Receipt')
            ->with(['letterType', 'category', 'status', 'priority', 'currentHolder', 'addressedTo', 'toDivision'])
            ->visibleTo($user);

        $correspondences = $query->orderByDesc('received_date')->get();

        return view('correspondence.reports.receipt-report', [
            'correspondences' => $correspondences,
            'statuses' => CorrespondenceStatus::active()->whereIn('type', ['Receipt', 'Both'])->ordered()->get(),
            'priorities' => CorrespondencePriority::active()->ordered()->get(),
            'letterTypes' => LetterType::active()->get(),
            'categories' => CorrespondenceCategory::active()->get(),
            'divisions' => Division::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'designation']),
        ]);
    }

    /**
     * Generate Dispatch Report.
     */
    public function dispatchReport(Request $request)
    {
        // Set default date range if not present
        if (!$request->has('filter.dispatch_from')) {
            $request->merge([
                'filter' => array_merge($request->get('filter', []), [
                    'dispatch_from' => now()->startOfMonth()->format('Y-m-d'),
                    'dispatch_to' => now()->format('Y-m-d'),
                ])
            ]);
        }

        $user = auth()->user();

        $query = QueryBuilder::for(Correspondence::query())
            ->allowedFilters([
                AllowedFilter::partial('dispatch_no'),
                AllowedFilter::partial('register_number'),
                AllowedFilter::partial('subject'),
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('priority_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('letter_type_id'),
                AllowedFilter::exact('to_division_id'),
                AllowedFilter::callback('dispatch_from', fn ($query, $value) => $value ? $query->whereDate('dispatch_date', '>=', $value) : null),
                AllowedFilter::callback('dispatch_to', fn ($query, $value) => $value ? $query->whereDate('dispatch_date', '<=', $value) : null),
                AllowedFilter::callback('pending', fn ($query, $value) => $value ? $query->whereHas('status', fn ($q) => $q->where('is_final', false)) : null),
            ])
            ->where('type', 'Dispatch')
            ->with(['letterType', 'category', 'status', 'priority', 'toDivision'])
            ->visibleTo($user);

        $correspondences = $query->orderByDesc('dispatch_date')->get();

        return view('correspondence.reports.dispatch-report', [
            'correspondences' => $correspondences,
            'statuses' => CorrespondenceStatus::active()->whereIn('type', ['Dispatch', 'Both'])->ordered()->get(),
            'priorities' => CorrespondencePriority::active()->ordered()->get(),
            'letterTypes' => LetterType::active()->get(),
            'categories' => CorrespondenceCategory::active()->get(),
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    /**
     * Generate User-wise Summary Report.
     */
    public function userWiseReport(Request $request)
    {
        // Get all users who have any activity or are active
        $userIds = array_unique(array_merge(
            Correspondence::pluck('addressed_to_user_id')->filter()->toArray(),
            Correspondence::pluck('marked_to_user_id')->filter()->toArray(),
            Correspondence::pluck('current_holder_id')->filter()->toArray()
        ));

        $users = User::whereIn('id', $userIds)
            ->orWhere('is_active', 'Yes')
            ->orderBy('name')
            ->get();

        $statuses = CorrespondenceStatus::active()->whereIn('type', ['Receipt', 'Both'])->ordered()->get();
        
        // Grouped queries to avoid N+1
        $addressedCounts = Correspondence::groupBy('addressed_to_user_id')
            ->selectRaw('addressed_to_user_id, count(*) as total')
            ->pluck('total', 'addressed_to_user_id');
            
        $markedCounts = Correspondence::groupBy('marked_to_user_id')
            ->selectRaw('marked_to_user_id, count(*) as total')
            ->pluck('total', 'marked_to_user_id');
            
        $heldCounts = Correspondence::groupBy('current_holder_id')
            ->selectRaw('current_holder_id, count(*) as total')
            ->pluck('total', 'current_holder_id');
            
        $statusCounts = Correspondence::groupBy(['current_holder_id', 'status_id'])
            ->selectRaw('current_holder_id, status_id, count(*) as total')
            ->get()
            ->groupBy('current_holder_id');

        return view('correspondence.reports.user-wise', compact('users', 'statuses', 'addressedCounts', 'markedCounts', 'heldCounts', 'statusCounts'));
    }

    /**
     * Generate Monthly Summary Report.
     */
    public function monthlySummaryReport(Request $request)
    {
        $summary = Correspondence::selectRaw("current_holder_id, TO_CHAR(received_date, 'YYYY-MM') as month, count(*) as total")
            ->whereNotNull('current_holder_id')
            ->whereNotNull('received_date')
            ->groupBy('current_holder_id')
            ->groupByRaw("TO_CHAR(received_date, 'YYYY-MM')")
            ->orderBy('month', 'desc')
            ->with('currentHolder')
            ->get();
            
        return view('correspondence.reports.monthly-summary', compact('summary'));
    }
}
