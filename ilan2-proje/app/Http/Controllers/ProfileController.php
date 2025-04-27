<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Profil düzenleme sayfasını göster.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Profil bilgilerini güncelle.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|numeric',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
        ]);
        
        $user->update([
            'name' => $request->name,
            'description' => $request->description,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }

    /**
     * Kullanıcı tipini güncelle.
     */
    public function updateType(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'user_type' => 'required|in:gemici,yuk_veren',
        ]);
        
        $user->update([
            'user_type' => $request->user_type,
        ]);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Kullanıcı tipiniz başarıyla güncellendi.');
    }
    
    /**
     * Kullanıcının gemi rotalarını listele (gemici için).
     */
    public function gemiRoutes()
    {
        $user = Auth::user();
        
        if (!$user->isGemici()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Bu sayfaya erişim için gemici profilinizi tamamlamanız gerekiyor.');
        }
        
        $gemiRoutes = $user->gemiRoutes()->latest()->get();
        
        return view('gemi_routes.gemi-routes', compact('gemiRoutes'));
    }
    
    /**
     * Kullanıcının yüklerini listele (yük veren için).
     */
    public function yukler()
    {
        $user = Auth::user();
        
        if (!$user->isYukVeren()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Bu sayfaya erişim için yük veren profilinizi tamamlamanız gerekiyor.');
        }
        
        $yukler = $user->yukler()->latest()->get();
        
        return view('profile.yukler', compact('yukler'));
    }
}
