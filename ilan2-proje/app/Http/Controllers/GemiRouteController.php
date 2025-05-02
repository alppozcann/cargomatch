<?php

namespace App\Http\Controllers;

use App\Models\GemiRoute;
use App\Models\Ship;
use App\Models\Yuk;
use App\Models\Port;
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
            
            if ($user->isGemici()) {
                $routes = $user->gemiRoutes()->with(['matchedYukler', 'ship'])->latest()->get();
                
                foreach ($routes as $route) {
                    $route->start_port_name = Port::find($route->start_location)?->name ?? 'Bilinmiyor';
                    $route->end_port_name = Port::find($route->end_location)?->name ?? 'Bilinmiyor';
                }
            } else {
                $routes = collect();
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
        $ships = Ship::where('user_id', Auth::id())->get();
        return view('gemi_routes.create', compact('ports', 'ships'));
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
        dd($request->all());
        $shipId = $request->ship_id; // Kullanıcı formdan seçtiyse

        $existingRoute = GemiRoute::where('ship_id', $shipId)
        ->where(function ($query) use ($request) {
        $query->whereBetween('departure_date', [$request->departure_date, $request->arrival_date])
              ->orWhereBetween('arrival_date', [$request->departure_date, $request->arrival_date]);
    })
    ->first();

if ($existingRoute) {
    return back()->with('error', 'Bu gemi için belirtilen tarihler arasında zaten bir rota mevcut.');
}


        $request->validate([
            'title' => 'required|string|max:255',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
            'way_points' => 'nullable|array',
            'way_points.*.port_id' => 'required|integer',
            'way_points.*.date' => 'required|date',
            'available_capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
            'weight_type' => 'required|string|max:10',
            'currency_type' => 'required|string|max:10',
        ]);

        // Way points array'ini temizle (boş olanları kaldır)
        $wayPoints = array_values(array_filter($request->way_points ?? [], function ($item) {
            return !empty($item['port_id']) && !empty($item['date']);
        }));

        \Log::debug('Store request data', [
            'user_id' => Auth::id(),
            'ship_id' => $request->ship_id,
            'title' => $request->title,
            'start_location' => $request->start_location,
            'end_location' => $request->end_location,
            'way_points' => $request->way_points,
            'available_capacity' => $request->available_capacity,
            'price' => $request->price,
            'departure_date' => $request->departure_date,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
            'weight_type' => $request->weight_type,
            'currency_type' => $request->currency_type,
        ]);

        $gemiRoute = GemiRoute::create([
            'user_id' => Auth::id(),
            'ship_id' => $request->ship_id,
            'title' => $request->title,
            'start_location' => $request->start_location,
            'end_location' => $request->end_location,
            'way_points' => $wayPoints,
            'available_capacity' => $request->available_capacity,
            'price' => $request->price,
            'departure_date' => $request->departure_date,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
            'weight_type' => $request->weight_type,
            'currency_type' => $request->currency_type,
            'status' => 'active',
        ]);
        \Log::info('Yeni rota ID:', ['id' => $gemiRoute?->id]);

        return $gemiRoute
            ? redirect()->route('gemi_routes.show', $gemiRoute)->with('success', 'Gemi rotası başarıyla eklendi.')
            : back()->with('error', 'Bir hata oluştu, rota kaydedilemedi.');
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
        // Sadece rota ile uyumlu yükleri göster
        $matchingYukler = $matchingYukler->filter(function ($yuk) use ($gemiRoute) {
            $allRoutePorts = array_merge(
                [$gemiRoute->start_location],
                $gemiRoute->way_points ?? [],
                [$gemiRoute->end_location]
            );
            return in_array($yuk->start_location, $allRoutePorts) && in_array($yuk->end_location, $allRoutePorts);
        });
    
        // Başlangıç ve varış limanlarını isim olarak al
        $startPort = \App\Models\Port::find($gemiRoute->start_location);
        $endPort = \App\Models\Port::find($gemiRoute->end_location);
    
        // Ara durakları isim olarak al
        $waypoints = [];
        if (is_array($gemiRoute->way_points)) {
            foreach ($gemiRoute->way_points as $waypointId) {
                $port = \App\Models\Port::find($waypointId);
                if ($port) {
                    $waypoints[] = $port->name;
                }
            }
        }
        
        // Gemi bilgilerini al
        $ship = null;
        $shipId = $gemiRoute->ship_id;
        if ($shipId) {
            $ship = Ship::find($shipId);
        }
    
        return view('gemi_routes.show', compact(
            'gemiRoute', 
            'matchingYukler', 
            'startPort', 
            'endPort', 
            'waypoints',
            'ship',
            'shipId'
        ));
    }
    

    /**
     * Gemi rotasını düzenleme formunu gösterir.
     */
    public function edit(GemiRoute $gemiRoute)
    {
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Bu rotayı düzenleme yetkiniz yok.');
        }
    
        $ports = \App\Models\Port::orderBy('name')->get(); // Limanları al
        return view('gemi_routes.edit', compact('gemiRoute', 'ports')); // Dikkat burada ports da var
    }
    

    /**
     * Gemi rotasını günceller.
     */
    public function update(Request $request, GemiRoute $gemiRoute)
    {
        if (Auth::id() !== $gemiRoute->user_id) {
            return redirect()->route('gemi_routes.index')
                ->with('error', 'Bu rotayı güncelleme yetkiniz yok.');
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'way_points' => 'nullable|array',
            'way_points.*' => 'nullable|string|max:255',
            'available_capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'description' => 'nullable|string',
            'weight_type' => 'required|string|max:10',
            'currency_type' => 'required|string|max:10',
        ]);
    
        $wayPoints = array_filter($request->way_points ?? []);
    
        $gemiRoute->update([
            'title' => $request->title,
            'way_points' => $wayPoints,
            'available_capacity' => $request->available_capacity,
            'price' => $request->price,
            'departure_date' => $request->departure_date,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
            'weight_type' => $request->weight_type,
            'currency_type' => $request->currency_type,
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

public function rejectMatch(Yuk $yuk)
{
    $gemiRoute = $yuk->matchedGemiRoute;

    if (!$gemiRoute || auth()->id() !== $gemiRoute->user_id) {
        return back()->with('error', 'Bu eşleştirmeyi reddetme yetkiniz yok.');
    }

    if ($yuk->match_status !== 'pending') {
        return back()->with('error', 'Bu yük zaten işleme alınmış.');
    }

    $yuk->update([
        'match_status' => 'rejected',
        'status' => 'active',
        'matched_gemi_route_id' => null,
    ]);

    return back()->with('success', 'Eşleşme reddedildi.');
}
}
