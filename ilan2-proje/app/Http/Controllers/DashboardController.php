<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Port; // Port modelini ekle
use App\Models\GemiRoute;
use App\Models\Yuk;
use App\Models\Ship;

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
            $gemiCount = $user->gemiler()->count();

            foreach ($rotalar as $rota) {
                $rota->start_port_name = Port::find($rota->start_location)?->name ?? 'Bilinmiyor';
                $rota->end_port_name = Port::find($rota->end_location)?->name ?? 'Bilinmiyor';
            }
            $matchedYukCount = $rotalar->flatMap->matchedYukler->where('status', 'matched')->count();
            $matchedYukCount = $matchedYukCount > 0 ? $matchedYukCount : 0;
            return view('dashboard.gemici', compact('user', 'rotalar', 'matchedYukCount', 'gemiCount'));
        }

        abort(403);
    }
    public function matchedProducts()
{
    $user = auth()->user();

    $matchedYukler = \App\Models\Yuk::whereNotNull('matched_gemi_route_id')
        ->whereHas('matchedGemiRoute', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->get();

    return view('dashboard.matched-products', compact('matchedYukler'));
}
}