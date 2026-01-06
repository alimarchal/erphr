<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorrespondenceRequest;
use App\Http\Requests\UpdateCorrespondenceRequest;
use App\Models\Branch;
use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\Division;
use App\Models\LetterType;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CorrespondenceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view correspondence', only: ['index', 'show']),
            new Middleware('can:create correspondence', only: ['create', 'store']),
            new Middleware('can:edit correspondence', only: ['edit', 'update']),
            new Middleware('can:delete correspondence', only: ['destroy']),
            new Middleware('can:mark correspondence', only: ['mark']),
            new Middleware('can:move correspondence', only: ['updateMovement']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type'); // null means show both
        $user = auth()->user();

        $query = QueryBuilder::for(Correspondence::query())
            ->allowedFilters([
                AllowedFilter::partial('register_number'),
                AllowedFilter::partial('reference_number'),
                AllowedFilter::partial('subject'),
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('priority_id'),
                AllowedFilter::exact('letter_type_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::partial('sender_name'),
                AllowedFilter::exact('current_holder_id'),
                AllowedFilter::exact('marked_to_user_id'),
                AllowedFilter::exact('addressed_to_user_id'),
                AllowedFilter::exact('to_division_id'),
                AllowedFilter::exact('confidentiality'),
                AllowedFilter::exact('year'),
                AllowedFilter::exact('type'),
                AllowedFilter::callback('received_from', fn ($query, $value) => $value ? $query->whereDate('received_date', '>=', $value) : null),
                AllowedFilter::callback('received_to', fn ($query, $value) => $value ? $query->whereDate('received_date', '<=', $value) : null),
                AllowedFilter::callback('overdue', fn ($query, $value) => $value ? $query->overdue() : null),
            ])
            ->with(['letterType', 'category', 'status', 'priority', 'currentHolder', 'toDivision', 'creator']);

        // Apply visibility scope
        $query->visibleTo($user);

        // Apply type filter if specified
        if ($type) {
            $query->where('type', $type);
        }

        $correspondences = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('correspondence.index', [
            'correspondences' => $correspondences,
            'type' => $type,
            'statuses' => CorrespondenceStatus::active()->ordered()->get(),
            'priorities' => CorrespondencePriority::active()->ordered()->get(),
            'letterTypes' => LetterType::active()->get(),
            'categories' => CorrespondenceCategory::active()->get(),
            'divisions' => Division::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
            'branches' => Branch::with('region')->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'designation']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'Receipt');

        return view('correspondence.create', [
            'type' => $type,
            'letterTypes' => LetterType::active()->get(),
            'categories' => CorrespondenceCategory::active()->get(),
            'priorities' => CorrespondencePriority::active()->ordered()->get(),
            'statuses' => $type === 'Receipt'
                ? CorrespondenceStatus::active()->forReceipt()->ordered()->get()
                : CorrespondenceStatus::active()->forDispatch()->ordered()->get(),
            'divisions' => Division::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
            'branches' => Branch::with('region')->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'designation']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCorrespondenceRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // If receipt, default to_division_id to HRMD if not provided
            if ($data['type'] === 'Receipt' && empty($data['to_division_id'])) {
                $hrmd = Division::where('short_name', 'HRMD')->first();
                if ($hrmd) {
                    $data['to_division_id'] = $hrmd->id;
                }
            }

            // Set initial status if not provided
            if (empty($data['status_id'])) {
                $initialStatus = CorrespondenceStatus::where('type', $data['type'])
                    ->orWhere('type', 'Both')
                    ->where('is_initial', true)
                    ->first();
                $data['status_id'] = $initialStatus?->id;
            }

            // Set current holder to marked user if provided
            if (! empty($data['marked_to_user_id'])) {
                $data['current_holder_id'] = $data['marked_to_user_id'];
                $data['current_holder_since'] = now();
            }

            $correspondence = Correspondence::create($data);

            // Create initial movement if marked to someone
            if (! empty($data['marked_to_user_id'])) {
                $correspondence->movements()->create([
                    'from_user_id' => auth()->id(),
                    'to_user_id' => $data['marked_to_user_id'],
                    'to_division_id' => $data['to_division_id'] ?? null,
                    'action' => $data['initial_action'] ?? 'Mark',
                    'instructions' => 'Initial marking upon receipt.',
                    'sequence' => 1,
                ]);
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $correspondence->addMedia($file)->toMediaCollection('attachments');
                }
            }

            DB::commit();

            $typeLabel = $data['type'] === 'Receipt' ? 'Receipt' : 'Dispatch';

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', "{$typeLabel} '{$correspondence->register_number}' created successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error creating correspondence', [
                'payload' => $request->except('attachments'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', [
                    'message' => 'Unable to create correspondence. Please try again.',
                    'db' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error creating correspondence', [
                'payload' => $request->except('attachments'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create correspondence. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Correspondence $correspondence)
    {
        $this->authorize('view', $correspondence);

        $correspondence->load([
            'letterType',
            'category',
            'status',
            'priority',
            'currentHolder',
            'addressedTo',
            'toDivision',
            'fromDivision',
            'region',
            'branch',
            'movements.fromUser',
            'movements.toUser',
            'movements.comments.user',
            'movements.media',
            'parent',
            'replies',
            'creator',
            'updater',
            'media',
        ]);

        return view('correspondence.show', [
            'correspondence' => $correspondence,
            'users' => User::orderBy('name')->get(['id', 'name', 'designation']),
            'statuses' => CorrespondenceStatus::active()
                ->where(function ($q) use ($correspondence) {
                    $q->where('type', $correspondence->type)->orWhere('type', 'Both');
                })->ordered()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Correspondence $correspondence)
    {
        $this->authorize('update', $correspondence);

        return view('correspondence.edit', [
            'correspondence' => $correspondence,
            'type' => $correspondence->type,
            'letterTypes' => LetterType::active()->get(),
            'categories' => CorrespondenceCategory::active()->get(),
            'priorities' => CorrespondencePriority::active()->ordered()->get(),
            'statuses' => $correspondence->type === 'Receipt'
                ? CorrespondenceStatus::active()->forReceipt()->ordered()->get()
                : CorrespondenceStatus::active()->forDispatch()->ordered()->get(),
            'divisions' => Division::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
            'branches' => Branch::with('region')->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'designation']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorrespondenceRequest $request, Correspondence $correspondence)
    {
        $this->authorize('update', $correspondence);

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $correspondence->update($data);

            // Handle new file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $correspondence->addMedia($file)->toMediaCollection('attachments');
                }
            }

            DB::commit();

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', "Correspondence '{$correspondence->register_number}' updated successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error updating correspondence', [
                'correspondence_id' => $correspondence->id,
                'payload' => $request->except('attachments'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', [
                    'message' => 'Unable to update correspondence. Please try again.',
                    'db' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error updating correspondence', [
                'correspondence_id' => $correspondence->id,
                'payload' => $request->except('attachments'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update correspondence. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correspondence $correspondence)
    {
        $this->authorize('delete', $correspondence);

        DB::beginTransaction();

        try {
            $registerNumber = $correspondence->register_number;
            $type = $correspondence->type;
            $correspondence->delete();

            DB::commit();

            return redirect()
                ->route('correspondence.index', ['type' => $type])
                ->with('success', "Correspondence '{$registerNumber}' deleted successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error deleting correspondence', [
                'correspondence_id' => $correspondence->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', [
                'message' => 'Unable to delete correspondence.',
                'db' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error deleting correspondence', [
                'correspondence_id' => $correspondence->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to delete correspondence. Please try again.');
        }
    }

    /**
     * Mark correspondence to a user.
     */
    public function mark(Request $request, Correspondence $correspondence)
    {
        $this->authorize('update', $correspondence);

        $request->validate([
            'to_user_id' => ['required', 'exists:users,id'],
            'action' => ['required', 'string'],
            'instructions' => ['nullable', 'string'],
            'is_urgent' => ['boolean'],
            'expected_response_date' => ['nullable', 'date'],
            'status_id' => ['nullable', 'exists:correspondence_statuses,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:15360'], // 15MB max per file
        ]);

        DB::beginTransaction();

        try {
            $toUser = User::findOrFail($request->to_user_id);

            $movement = $correspondence->markTo(
                $toUser,
                $request->action,
                $request->instructions
            );

            if ($request->is_urgent) {
                $movement->update(['is_urgent' => true]);
            }

            if ($request->expected_response_date) {
                $movement->update(['expected_response_date' => $request->expected_response_date]);
            }

            // Handle file attachments for the movement
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $movement->addMedia($file)->toMediaCollection('attachments');
                }
            }

            // Update status if provided, otherwise default to MARKED
            if ($request->status_id) {
                $correspondence->update(['status_id' => $request->status_id]);
            } else {
                $markedStatus = CorrespondenceStatus::where('code', 'MARKED')->first();
                if ($markedStatus) {
                    $correspondence->update(['status_id' => $markedStatus->id]);
                }
            }

            DB::commit();

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', "Marked to {$toUser->name} successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error marking correspondence', [
                'correspondence_id' => $correspondence->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to mark correspondence. Please try again.');
        }
    }

    /**
     * Update movement status (receive, review, action).
     */
    public function updateMovement(Request $request, Correspondence $correspondence)
    {
        $this->authorize('updateMovement', $correspondence);

        $request->validate([
            'movement_id' => ['required', 'exists:correspondence_movements,id'],
            'action' => ['required', 'in:receive,review,complete'],
            'action_taken' => ['required_if:action,complete', 'nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            $movement = $correspondence->movements()->findOrFail($request->movement_id);

            match ($request->action) {
                'receive' => $movement->markAsReceived(),
                'review' => $movement->markAsReviewed(),
                'complete' => $movement->markAsActioned($request->action_taken),
            };

            if ($request->remarks) {
                $movement->update(['remarks' => $request->remarks]);
            }

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', 'Movement updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Error updating movement', [
                'correspondence_id' => $correspondence->id,
                'movement_id' => $request->movement_id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to update movement. Please try again.');
        }
    }

    /**
     * Update correspondence status independently.
     */
    public function updateStatus(Request $request, Correspondence $correspondence)
    {
        $this->authorize('updateMovement', $correspondence);

        $request->validate([
            'status_id' => ['required', 'exists:correspondence_statuses,id'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $correspondence->status?->name ?? 'None';
            $correspondence->update(['status_id' => $request->status_id]);
            $correspondence->refresh();
            $newStatus = $correspondence->status->name;

            // Create a movement log for status change
            $correspondence->movements()->create([
                'from_user_id' => auth()->id(),
                'to_user_id' => auth()->id(),
                'action' => 'Status Update',
                'instructions' => "Changed status from '{$oldStatus}' to '{$newStatus}'".($request->remarks ? ". Note: {$request->remarks}" : ''),
                'status' => 'Actioned', // Mark as completed log
                'sequence' => ($correspondence->movements()->max('sequence') ?? 0) + 1,
                'action_taken' => 'Status changed',
                'action_taken_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', 'Correspondence status updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating status', [
                'correspondence_id' => $correspondence->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update status.');
        }
    }

    /**
     * Add a comment to the latest movement.
     */
    public function addComment(Request $request, Correspondence $correspondence)
    {
        $this->authorize('view', $correspondence);

        $request->validate([
            'comment' => ['required', 'string'],
        ]);

        try {
            $latestMovement = $correspondence->movements()->latest()->first();

            if (! $latestMovement) {
                return back()->with('error', 'No movement found to attach comment.');
            }

            $latestMovement->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment,
            ]);

            return redirect()
                ->route('correspondence.show', $correspondence)
                ->with('success', 'Comment added successfully.');
        } catch (\Throwable $e) {
            Log::error('Error adding comment', [
                'correspondence_id' => $correspondence->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to add comment.');
        }
    }
}
