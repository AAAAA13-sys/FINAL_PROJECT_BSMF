<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use Illuminate\Support\Str;

class LogAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'])) {
            // Filter out requests that are not pages or important actions (like assets, livewire updates)
            if (!$request->ajax() && !$request->is('livewire/*') && !$request->is('_debugbar/*')) {
                // If it's a GET request to a page, we log it as "PAGE_VISIT"
                if ($request->isMethod('GET')) {
                    $action = 'PAGE_VISIT';
                    $description = 'Visited page: ' . $request->path();
                } else {
                    $action = 'ACTION_' . strtoupper($request->method());
                    $description = 'Performed ' . $request->method() . ' on ' . $request->path();
                }

                // If it's the audit logs page itself, maybe we don't need to spam it, but user wants ALL.
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => $action,
                    'description' => Str::limit($description, 255),
                    'model_type' => null,
                    'model_id' => null,
                    'old_values' => null,
                    'new_values' => $request->except(['password', 'password_confirmation', '_token']),
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        return $response;
    }
}
