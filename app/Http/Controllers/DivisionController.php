<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\Division;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DivisionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage divisions'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $divisions = QueryBuilder::for(Division::query())
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('short_name'),
                AllowedFilter::callback('created_from', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '>=', $value) : null),
                AllowedFilter::callback('created_to', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '<=', $value) : null),
            ])
            ->with(['creator', 'updater'])
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('settings.divisions.index', [
            'divisions' => $divisions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.divisions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request)
    {
        DB::beginTransaction();

        try {
            $division = Division::create($request->validated());

            DB::commit();

            return redirect()
                ->route('divisions.index')
                ->with('success', "Division '{$division->name}' created successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error creating division', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $message = 'Unable to create division. Please review your input and try again.';
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'divisions_name_unique')) {
                    $message = 'A division with this name already exists.';
                } elseif (str_contains($e->getMessage(), 'divisions_short_name_unique')) {
                    $message = 'A division with this short name already exists.';
                }
            }

            return back()
                ->withInput()
                ->with('error', [
                    'message' => $message,
                    'db' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error creating division', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create division. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        $division->load(['creator', 'updater']);

        return view('settings.divisions.show', [
            'division' => $division,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        return view('settings.divisions.edit', [
            'division' => $division,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request, Division $division)
    {
        DB::beginTransaction();

        try {
            $updated = $division->update($request->validated());

            if (! $updated) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('info', 'No changes were made to the division.');
            }

            DB::commit();

            return redirect()
                ->route('divisions.index')
                ->with('success', "Division '{$division->name}' updated successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error updating division', [
                'division_id' => $division->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $message = 'Unable to update division. Please review your input and try again.';
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'divisions_name_unique')) {
                    $message = 'A division with this name already exists.';
                } elseif (str_contains($e->getMessage(), 'divisions_short_name_unique')) {
                    $message = 'A division with this short name already exists.';
                }
            }

            return back()
                ->withInput()
                ->with('error', [
                    'message' => $message,
                    'db' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error updating division', [
                'division_id' => $division->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update division. Please try again.');
        }
    }
}
