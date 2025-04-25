<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('AdminMiddleware: Checking authentication');
        
        if (!Auth::check()) {
            Log::info('AdminMiddleware: User not authenticated');
            return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $user = Auth::user();
        Log::info('AdminMiddleware: User authenticated', [
            'user_id' => $user->id,
            'is_admin' => $user->is_admin,
            'email' => $user->email
        ]);

        if (!$user->is_admin) {
            Log::info('AdminMiddleware: User is not admin');
            return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
        }

        Log::info('AdminMiddleware: User is admin, proceeding');
        return $next($request);
    }
}
