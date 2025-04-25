<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Kayıt formunu gösterir.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Yeni bir kullanıcı kaydeder.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone_number' => 'nullable|numeric',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'user_type' => 'required|in:yukveren,gemici',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'user_type' => $request->user_type,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
