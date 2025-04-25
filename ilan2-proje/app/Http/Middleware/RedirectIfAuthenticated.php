<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Admin kullanıcıları admin dashboard'a yönlendir
                if (Auth::user()->is_admin) {
                    return redirect(route('admin.dashboard'));
                }
                
                // Normal kullanıcıları dashboard'a yönlendir
                return redirect(route('dashboard'));
            }
        }

        // Eğer kullanıcı giriş yapmamışsa ve welcome sayfasına erişmeye çalışıyorsa, devam et
        if ($request->route()->getName() === 'welcome') {
            return $next($request);
        }

        // Diğer tüm durumlarda welcome sayfasına yönlendir
        return redirect(route('welcome'));
    }
}
