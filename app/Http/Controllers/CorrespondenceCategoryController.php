<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorrespondenceCategoryRequest;
use App\Http\Requests\UpdateCorrespondenceCategoryRequest;
use App\Models\CorrespondenceCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CorrespondenceCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage correspondence categories'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = QueryBuilder::for(CorrespondenceCategory::query())
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('code'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::callback('created_from', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '>=', $value) : null),
                AllowedFilter::callback('created_to', fn ($query, $value) => $value !== null && $value !== '' ? $query->whereDate('created_at', '<=', $value) : null),
            ])
            ->with(['parent', 'creator', 'updater'])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('correspondence.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CorrespondenceCategory::where('is_active', true)->orderBy('name')->get();

        return view('correspondence.categories.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCorrespondenceCategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $category = CorrespondenceCategory::create($request->validated());

            DB::commit();

            return redirect()
                ->route('correspondence-categories.index')
                ->with('success', "Category '{$category->name}' created successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error creating correspondence category', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $message = 'Unable to create category. Please review your input and try again.';
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'correspondence_categories_code')) {
                    $message = 'A category with this code already exists.';
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

            Log::error('Unexpected error creating correspondence category', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CorrespondenceCategory $correspondenceCategory)
    {
        $correspondenceCategory->load(['parent', 'children', 'creator', 'updater']);

        return view('correspondence.categories.show', [
            'category' => $correspondenceCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CorrespondenceCategory $correspondenceCategory)
    {
        $categories = CorrespondenceCategory::where('is_active', true)
            ->where('id', '!=', $correspondenceCategory->id)
            ->orderBy('name')
            ->get();

        return view('correspondence.categories.edit', [
            'category' => $correspondenceCategory,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorrespondenceCategoryRequest $request, CorrespondenceCategory $correspondenceCategory)
    {
        DB::beginTransaction();

        try {
            $updated = $correspondenceCategory->update($request->validated());

            if (! $updated) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('info', 'No changes were made to the category.');
            }

            DB::commit();

            return redirect()
                ->route('correspondence-categories.index')
                ->with('success', "Category '{$correspondenceCategory->name}' updated successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error updating correspondence category', [
                'category_id' => $correspondenceCategory->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            $message = 'Unable to update category. Please review your input and try again.';
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'correspondence_categories_code')) {
                    $message = 'A category with this code already exists.';
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

            Log::error('Unexpected error updating correspondence category', [
                'category_id' => $correspondenceCategory->id,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Toggle the active status of the category.
     */
    public function toggle(CorrespondenceCategory $correspondenceCategory)
    {
        DB::beginTransaction();

        try {
            $correspondenceCategory->update([
                'is_active' => ! $correspondenceCategory->is_active,
            ]);

            DB::commit();

            $status = $correspondenceCategory->is_active ? 'activated' : 'deactivated';

            return redirect()
                ->route('correspondence-categories.index')
                ->with('success', "Category '{$correspondenceCategory->name}' {$status} successfully.");
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error('Database error toggling correspondence category', [
                'category_id' => $correspondenceCategory->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Unable to toggle category status.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected error toggling correspondence category', [
                'category_id' => $correspondenceCategory->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Failed to toggle category status. Please try again.');
        }
    }
}
