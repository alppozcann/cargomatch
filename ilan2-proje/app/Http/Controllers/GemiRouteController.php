<?php

namespace App\Http\Controllers;

use App\Models\GemiRoute;
use App\Models\Yuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GemiRouteController extends Controller
{
    /**
     * Tüm gemi rotalarını listeler.
     */
    public function index()
    {
        $user = auth()->user();
    
        if (auth()->check && $user->isGemici()) {
            $routes = $user->gemiRoutes()->with('matchedYukler')->latest()->get();
        } else {
            $routes = collect(); // Diğerleri göremez
        }
    
        return view('gemi_routes.index', compact('routes'));
    }
    /**
     * Yeni bir gemi rotası eklemek için form gösterir.
     */
    public function create()
    {
        // Sadece gemici tipi kullanıcılar rota ekleyebilir
        if (!Auth::user()->isGemici()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Rota eklemek için gemici profilinizi tamamlamanız gerekiyor.');
        }
        $ports = \App\Models\Port::orderBy('name')->get();
        return view('gemi_routes.create', compact('ports'));
    }

    /**
     * Yeni eklenen gemi rotasını kaydeder.
     */
    public function store(Request $request)
    {
        // Sadece gemici tipi kullanıcılar rota ekleyebilir
        if (!Auth::user()->isGemici()) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Rota eklemek için gemici profilinizi tamamlamanız gerekiyor.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'way_points' => 'nullable|array',
            'way_points.*' => 'nullable|string|max:255',
            'available_capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
        ]);

        // Way points array'ini temizle (boş olanları kaldır)
        $wayPoints = array_filter($request->way_points ?? []);

        $gemiRoute = GemiRoute::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'start_location' => $request->start_location,
            'end_location' => $request->end_location,
            'way_points' => $wayPoints,
            'available_capacity' => $request->available_capacity,
            'price' => $request->price,
            'departure_date' => $request->departure_date,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('gemi_routes.show', $gemiRoute)
            ->with('success', 'Gemi rotası başarıyla eklendi.');
    }

    /**
     * Belirli bir gemi rotasını gösterir.
     */
    public function show(GemiRoute $gemiRoute)
    {
        if (auth()->id() !== $gemiRoute->user_id) {
            abort(403); // Yetkisiz erişim
        }

        // ShipCargoMatchingService'i kullanarak eşleşebilecek yükleri bul
        $matchingService = new \App\Services\ShipCargoMatchingService();
        $matchingYukler = $matchingService->findMatchingCargoForShip($gemiRoute);
        
        return view('gemi_routes.show', compact('gemiRoute', 'matchingYukler'));
    }

    /**
     * Gemi rotasını düzenleme formunu gösterir.
     */
    public function edit(GemiRoute $gemiRoute)
    {
        // Sadece ilan sahibi düzenleyebilir
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Bu rotayı düzenleme yetkiniz yok.');
        }

        return view('gemi_routes.edit', compact('gemiRoute'));
    }

    /**
     * Gemi rotasını günceller.
     */
    public function update(Request $request, GemiRoute $gemiRoute)
    {
        // Sadece ilan sahibi güncelleyebilir
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Bu rotayı güncelleme yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'way_points' => 'nullable|array',
            'way_points.*' => 'nullable|string|max:255',
            'available_capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
        ]);

        // Way points array'ini temizle (boş olanları kaldır)
        $wayPoints = array_filter($request->way_points ?? []);

        $gemiRoute->update([
            'title' => $request->title,
            'start_location' => $request->start_location,
            'end_location' => $request->end_location,
            'way_points' => $wayPoints,
            'available_capacity' => $request->available_capacity,
            'price' => $request->price,
            'departure_date' => $request->departure_date,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
        ]);

        return redirect()->route('gemi_routes.show', $gemiRoute)
            ->with('success', 'Gemi rotası başarıyla güncellendi.');
    }

    /**
     * Gemi rotasını siler.
     */
    public function destroy(GemiRoute $gemiRoute)
    {
        // Sadece ilan sahibi silebilir
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Bu rotayı silme yetkiniz yok.');
        }

        $gemiRoute->delete();

        return redirect()->route('gemi_routes.index')
            ->with('success', 'Gemi rotası başarıyla silindi.');
    }

    /**
     * Bir yük ile gemi rotasını eşleştir.
     */
    public function matchYuk(GemiRoute $gemiRoute, Yuk $yuk)
    {
        // Sadece gemi sahibi eşleştirme yapabilir
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.show', $gemiRoute)
                ->with('error', 'Bu rota için eşleştirme yapma yetkiniz yok.');
        }

        // Yükün durumunu kontrol et
        if ($yuk->status !== 'active') {
            return redirect()->route('gemi_routes.show', $gemiRoute)
                ->with('error', 'Bu yük zaten eşleştirilmiş veya aktif değil.');
        }

        // Kapasite kontrolü
        if ($yuk->weight > $gemiRoute->available_capacity) {
            return redirect()->route('gemi_routes.show', $gemiRoute)
                ->with('error', 'Bu yük için yeterli kapasiteniz yok.');
        }

        // Yükü eşleştir
        $yuk->update([
            'status' => 'matched',
            'matched_gemi_route_id' => $gemiRoute->id,
        ]);

        // Gemi rotasının kapasitesini güncelle
        $gemiRoute->update([
            'available_capacity' => $gemiRoute->available_capacity - $yuk->weight,
        ]);

        return redirect()->route('gemi_routes.show', $gemiRoute)
            ->with('success', 'Yük başarıyla bu rotaya eklendi.');
    }

    public function approveMatch(Yuk $yuk)
{
    $gemiRoute = $yuk->matchedGemiRoute;

    if (!$gemiRoute || auth()->id() !== $gemiRoute->user_id) {
        return back()->with('error', 'Bu eşleştirmeyi onaylama yetkiniz yok.');
    }

    if ($yuk->match_status !== 'pending') {
        return back()->with('error', 'Bu yük zaten onaylanmış veya teslim edilmiş.');
    }

    $yuk->update([
        'match_status' => 'confirmed',
        'status' => 'matched', // optional
    ]);

    $gemiRoute->update([
        'available_capacity' => $gemiRoute->available_capacity - $yuk->weight,
    ]);

    return back()->with('success', 'Eşleşme onaylandı. Süreç %50 tamamlandı.');
}
public function confirmDeliveryComplete(Yuk $yuk)
{
    if (!$yuk->matchedGemiRoute || auth()->id() !== $yuk->matchedGemiRoute->user_id) {
        return back()->with('error', 'Bu işlemi yalnızca ilgili gemici yapabilir.');
    }

    if ($yuk->match_status !== 'delivering') {
        return back()->with('error', 'Teslimat henüz yük sahibi tarafından başlatılmamış.');
    }

    $yuk->update([
        'match_status' => 'delivered',
        'status' => 'completed',
    ]);

    return back()->with('success', 'Teslimat tamamlandı. Süreç %100.');
}
}
