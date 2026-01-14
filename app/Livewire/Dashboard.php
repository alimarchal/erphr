<?php

namespace App\Livewire;

use App\Models\Correspondence;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class Dashboard extends Component
{
    #[Url]
    public $fromDate;

    #[Url]
    public $toDate;

    public $isAdmin = false;

    public function mount(): void
    {
        $this->isAdmin = auth()->user()->hasRole(['super-admin', 'admin']);
        $this->fromDate = $this->fromDate ?? now()->startOfMonth()->format('Y-m-d');
        $this->toDate = $this->toDate ?? now()->format('Y-m-d');
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['fromDate', 'toDate'])) {
            $this->dispatch('refresh-charts');
        }
    }

    public function getStatsProperty(): array
    {
        $user = auth()->user();
        $query = Correspondence::query()
            ->whereBetween('created_at', [$this->fromDate.' 00:00:00', $this->toDate.' 23:59:59']);

        if (! $this->isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('current_holder_id', $user->id)
                    ->orWhere('addressed_to_user_id', $user->id)
                    ->orWhere('marked_to_user_id', $user->id);
            });
        }

        return [
            'total' => (clone $query)->count(),
            'receipts' => (clone $query)->where('type', 'Receipt')->count(),
            'dispatches' => (clone $query)->where('type', 'Dispatch')->count(),
            'pending' => (clone $query)->whereHas('status', fn ($q) => $q->where('is_final', false))->count(),
            'closed' => (clone $query)->whereHas('status', fn ($q) => $q->where('is_final', true))->count(),
            'overdue' => (clone $query)->whereNotNull('due_date')->where('due_date', '<', now())->whereHas('status', fn ($q) => $q->where('is_final', false))->count(),
            'urgent' => (clone $query)->whereHas('priority', fn ($q) => $q->whereIn('name', ['Urgent', 'High', 'Critical']))->count(),
            'replied' => (clone $query)->where('is_replied', true)->count(),
        ];
    }

    public function getChartDataProperty(): array
    {
        $user = auth()->user();
        $start = Carbon::parse($this->fromDate)->startOfDay();
        $end = Carbon::parse($this->toDate)->endOfDay();

        // 1. Volume Trend (Receipts vs Dispatches)
        $trendData = Correspondence::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw("SUM(CASE WHEN type = 'Receipt' THEN 1 ELSE 0 END) as receipts"),
            DB::raw("SUM(CASE WHEN type = 'Dispatch' THEN 1 ELSE 0 END) as dispatches")
        )
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('current_holder_id', $user->id)
                        ->orWhere('addressed_to_user_id', $user->id)
                        ->orWhere('marked_to_user_id', $user->id);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0 for a better trend visualization
        $trendResults = [];
        $current = $start->copy();
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $row = $trendData->firstWhere('date', $dateStr);

            $trendResults['dates'][] = $dateStr;
            $trendResults['receipts'][] = (int) ($row->receipts ?? 0);
            $trendResults['dispatches'][] = (int) ($row->dispatches ?? 0);

            $current->addDay();
        }

        // 2. Status Breakdown
        $statusBreakdown = Correspondence::select('status_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('status_id')
            ->with('status')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->status->name ?? 'Unknown',
                'value' => $item->total,
                'color' => $item->status->color ?? '#cbd5e1',
            ]);

        // 3. Division/Workload Breakdown (Admin Only)
        $workloadData = [];
        if ($this->isAdmin) {
            $workloadData = Correspondence::select('current_holder_id', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('status', fn ($q) => $q->where('is_final', false))
                ->groupBy('current_holder_id')
                ->with('currentHolder')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get()
                ->map(fn ($item) => [
                    'label' => $item->currentHolder?->name ?? 'System',
                    'value' => $item->total,
                ]);
        }

        // 4. Priority Breakdown
        $priorityBreakdown = Correspondence::select('priority_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('priority_id')
            ->with('priority')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->priority->name ?? 'Normal',
                'value' => $item->total,
                'color' => match ($item->priority->name ?? 'Normal') {
                    'Critical' => '#ef4444',
                    'Urgent' => '#f97316',
                    'High' => '#f59e0b',
                    'Normal' => '#3b82f6',
                    'Low' => '#10b981',
                    default => '#cbd5e1',
                },
            ]);

        // 5. Confidentiality Breakdown
        $confidentialityBreakdown = Correspondence::select('confidentiality', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->when(! $this->isAdmin, function ($q) use ($user) {
                $q->where('current_holder_id', $user->id);
            })
            ->groupBy('confidentiality')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->confidentiality ?: 'Normal',
                'value' => $item->total,
            ]);

        return [
            'trend' => [
                'dates' => $trendResults['dates'],
                'receipts' => $trendResults['receipts'],
                'dispatches' => $trendResults['dispatches'],
            ],
            'status' => [
                'labels' => $statusBreakdown->pluck('label')->toArray(),
                'values' => $statusBreakdown->pluck('value')->toArray(),
                'colors' => $statusBreakdown->pluck('color')->toArray(),
            ],
            'workload' => [
                'labels' => collect($workloadData)->pluck('label')->toArray(),
                'values' => collect($workloadData)->pluck('value')->toArray(),
            ],
            'priority' => [
                'labels' => $priorityBreakdown->pluck('label')->toArray(),
                'values' => $priorityBreakdown->pluck('value')->toArray(),
                'colors' => $priorityBreakdown->pluck('color')->toArray(),
            ],
            'confidentiality' => [
                'labels' => $confidentialityBreakdown->pluck('label')->toArray(),
                'values' => $confidentialityBreakdown->pluck('value')->toArray(),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'stats' => $this->stats,
            'chartData' => $this->chartData,
        ])->layout('layouts.app');
    }
}
