<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseSessionVariables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $ip = $request->ip();

            // Set MySQL session variables so triggers can access them
            DB::statement("SET @current_user_id = ?", [$userId]);
            DB::statement("SET @current_ip = ?", [$ip]);
        } else {
            DB::statement("SET @current_user_id = NULL");
            DB::statement("SET @current_ip = ?", [$request->ip()]);
        }

        return $next($request);
    }
}
