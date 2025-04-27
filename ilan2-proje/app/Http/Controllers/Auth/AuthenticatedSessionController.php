<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Giriş formunu gösterir.
     */
    public function create()
    {
	if(Auth::check()){
		if(Auth::user()->is_admin){
			return redirect()->route('admin.dashboard');
		}
		return redirect()->route('dashboard');
	}

        return view('auth.login');
    }

    /**
     * Kullanıcıyı doğrular ve giriş yapar.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        dd(Auth::attempt($credentials));

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Admin kullanıcılar için admin dashboard'a yönlendir
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            // Normal kullanıcılar için dashboard'a yönlendir
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler kayıtlarımızla eşleşmiyor.',
        ])->withInput();
    }

    /**
     * Kullanıcı çıkışı yapar.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
