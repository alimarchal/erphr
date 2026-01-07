<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\CheckUserStatus::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Ensure authorization exceptions are not ignored by the reporter
        $exceptions->stopIgnoring(\Illuminate\Auth\Access\AuthorizationException::class);
        $exceptions->stopIgnoring(\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class);

        $exceptions->report(function (\Throwable $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ||
                $e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {

                $user = auth()->user();
                $logData = [
                    'user_id' => $user ? $user->id : 'Guest',
                    'user_email' => $user ? $user->email : 'N/A',
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ];

                if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                    $logData['required_permission'] = $e->getRequiredPermissions();
                    $logData['required_roles'] = $e->getRequiredRoles();
                }

                \Illuminate\Support\Facades\Log::warning('Unauthorized access attempt detected.', $logData);

                if (function_exists('activity')) {
                    activity('unauthorized_access')
                        ->causedBy($user)
                        ->withProperties($logData)
                        ->log('Unauthorized access attempt on ' . request()->path());
                }
            }
        });
    })->create();
