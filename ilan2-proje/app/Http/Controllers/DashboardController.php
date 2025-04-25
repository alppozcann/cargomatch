<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    

public function index()
{
    $user = auth()->user();

<<<<<<< HEAD
    if ($user->isYukVeren()) {
=======
    if ($user->user_type === 'yukveren') {
>>>>>>> 9523c88 (initial commit2)
        $yukler = $user->yukler()->with('matchedGemiRoute')->latest()->get();
        return view('dashboard.yukveren', compact('user', 'yukler'));
    }

<<<<<<< HEAD
    if ($user->isGemiSahibi()) {
=======
    if ($user->user_type === 'gemici') {
>>>>>>> 9523c88 (initial commit2)
        $rotalar = $user->gemiRoutes()->with('matchedYukler')->latest()->get();
        return view('dashboard.gemisahibi', compact('user', 'rotalar'));
    }

    abort(403);
}

}