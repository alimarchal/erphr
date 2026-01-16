<?php

use App\Http\Controllers\CorrespondenceController;
use App\Http\Controllers\CorrespondenceReportController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return to_route('login');
})->name('home');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Divisions CRUD
    Route::resource('settings/divisions', DivisionController::class)->names('divisions');

    // Correspondence Categories CRUD
    Route::resource('correspondence-categories', \App\Http\Controllers\CorrespondenceCategoryController::class);
    Route::patch('correspondence-categories/{category}/toggle', [\App\Http\Controllers\CorrespondenceCategoryController::class, 'toggle'])->name('correspondence-categories.toggle');

    // User Management
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Admin Activity Logs
    Volt::route('admin/activity-logs', 'admin.activity-log-viewer')
        ->middleware(['role:super-admin'])
        ->name('admin.activity-logs');

    // Correspondence Module Routes
    Route::prefix('correspondence')->name('correspondence.')->group(function () {
        // Reports (MUST be before CRUD to avoid matching {correspondence} parameter)
        Route::middleware(['can:view correspondence reports'])->group(function () {
            Route::get('/reports', [CorrespondenceReportController::class, 'index'])->name('reports.index');

            Route::get('/reports/receipts', [CorrespondenceReportController::class, 'receiptReport'])
                ->middleware('can:view receipt report')
                ->name('reports.receipts');

            Route::get('/reports/dispatches', [CorrespondenceReportController::class, 'dispatchReport'])
                ->middleware('can:view dispatch report')
                ->name('reports.dispatches');

            Route::get('/reports/user-wise', [CorrespondenceReportController::class, 'userWiseReport'])
                ->middleware('can:view user summary report')
                ->name('reports.user-wise');

            Route::get('/reports/monthly-summary', [CorrespondenceReportController::class, 'monthlySummaryReport'])
                ->middleware('can:view monthly summary report')
                ->name('reports.monthly-summary');
        });

        // Main correspondence CRUD
        Route::get('/', [CorrespondenceController::class, 'index'])->name('index');
        Route::get('/create', [CorrespondenceController::class, 'create'])->name('create');
        Route::post('/', [CorrespondenceController::class, 'store'])->name('store');
        Route::get('/{correspondence}', [CorrespondenceController::class, 'show'])->name('show');
        Route::get('/{correspondence}/edit', [CorrespondenceController::class, 'edit'])->name('edit');
        Route::put('/{correspondence}', [CorrespondenceController::class, 'update'])->name('update');
        Route::delete('/{correspondence}', [CorrespondenceController::class, 'destroy'])->name('destroy');

        // Marking and movement actions
        Route::post('/{correspondence}/mark', [CorrespondenceController::class, 'mark'])->name('mark');
        Route::post('/{correspondence}/movement', [CorrespondenceController::class, 'updateMovement'])->name('movement.update');
        Route::post('/{correspondence}/status', [CorrespondenceController::class, 'updateStatus'])->name('status.update');
        Route::post('/{correspondence}/comment', [CorrespondenceController::class, 'addComment'])->name('comment.add');
    });
});
