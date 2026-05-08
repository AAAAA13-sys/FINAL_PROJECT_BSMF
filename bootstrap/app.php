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
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SetDatabaseSessionVariables::class);
        $middleware->web(append: [
            \App\Http\Middleware\LogAdminActivity::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'The BSMF database encountered a synchronization issue. Our mechanics are on it.',
                ], 500);
            }

            return back()->withInput()->with('error', 'SYSTEM ALERT: A database synchronization error occurred. The garage mechanics have been notified.');
        });
    })->create();
