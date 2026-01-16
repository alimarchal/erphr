<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLetterTypeRequest;
use App\Http\Requests\UpdateLetterTypeRequest;
use App\Models\LetterType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LetterTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage letter types'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $letterTypes = QueryBuilder::for(LetterType::query())
            ->allowedFilters(
                AllowedFilter::partial('name'),
                AllowedFilter::partial('code'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::exact('requires_reply'),
                AllowedFilter::callback('created_from', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '>=', $value) : null),
                AllowedFilter::callback('created_to', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '<=', $value) : null),
            )
            ->allowedSorts('name', 'code', 'created_at')
            ->paginate();

        return view('letter-types.index', [
            'letterTypes' => $letterTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('letter-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLetterTypeRequest $request)
    {
        DB::beginTransaction();

        try {
            $letterType = LetterType::create($request->validated());

            DB::commit();

            return redirect()
                ->route('letter-types.index')
                ->with('success', "Letter type '{$letterType->name}' created successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error creating letter type', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Unable to create letter type.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error creating letter type', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create letter type. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LetterType $letterType)
    {
        $letterType->load(['correspondences']);

        return view('letter-types.show', [
            'letterType' => $letterType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LetterType $letterType)
    {
        return view('letter-types.edit', [
            'letterType' => $letterType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterTypeRequest $request, LetterType $letterType)
    {
        DB::beginTransaction();

        try {
            $updated = $letterType->update($request->validated());

            if ($updated) {
                DB::commit();

                return redirect()
                    ->route('letter-types.index')
                    ->with('success', "Letter type '{$letterType->name}' updated successfully.");
            }

            DB::rollBack();

            return back()->with('error', 'No changes were made.');
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error updating letter type', [
                'letter_type_id' => $letterType->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Unable to update letter type.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error updating letter type', [
                'letter_type_id' => $letterType->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update letter type. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterType $letterType)
    {
        try {
            $name = $letterType->name;
            $letterType->forceDelete();

            return redirect()
                ->route('letter-types.index')
                ->with('success', "Letter type '{$name}' deleted successfully.");
        } catch (\Throwable $e) {
            Log::error('Error deleting letter type', [
                'letter_type_id' => $letterType->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to delete letter type.');
        }
    }

    /**
     * Toggle the active status of the letter type.
     */
    public function toggle(LetterType $letterType)
    {
        DB::beginTransaction();

        try {
            $letterType->update([
                'is_active' => ! $letterType->is_active,
            ]);

            DB::commit();

            $status = $letterType->is_active ? 'activated' : 'deactivated';

            return redirect()
                ->route('letter-types.index')
                ->with('success', "Letter type '{$letterType->name}' {$status} successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error toggling letter type', [
                'letter_type_id' => $letterType->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Unable to toggle letter type status.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error toggling letter type', [
                'letter_type_id' => $letterType->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to toggle letter type status. Please try again.');
        }
    }
}
