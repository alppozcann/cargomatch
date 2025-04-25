<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    

public function index()
{
    $user = auth()->user();

    if ($user->user_type === 'yukveren') {
        $yukler = $user->yukler()->with('matchedGemiRoute')->latest()->get();
        return view('dashboard.yukveren', compact('user', 'yukler'));
    }

    if ($user->user_type === 'gemici') {
        $rotalar = $user->gemiRoutes()->with('matchedYukler')->latest()->get();
        return view('dashboard.gemisahibi', compact('user', 'rotalar'));
    }

    abort(403);
}

}