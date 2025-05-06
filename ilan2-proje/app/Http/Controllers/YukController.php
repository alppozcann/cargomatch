<?php

namespace App\Http\Controllers;

use App\Models\Yuk;
use App\Models\Port;
use App\Models\Ship;
use App\Models\GemiRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ShipCargoMatchingService;

class YukController extends Controller
{
    /**
     * Tüm yükleri listele.
     */
    public function index()
    {
        $user = auth()->user();
    
        // Sadece kendi yüklerini görebilsin
        if ($user->isYukVeren()) {
            $yukler = $user->yukler()->with('user')->latest()->get();
            foreach ($yukler as $yuk) {
                $yuk->from_location = Port::find($yuk->from_location)?->name ?? 'Bilinmiyor';
                $yuk->to_location = Port::find($yuk->to_location)?->name ?? 'Bilinmiyor';
            }
        
        } else {
            // Gemi sahibi veya admin için görünmeyecek (gerekirse boş bırak)
            $yukler = collect(); // Boş koleksiyon döndür
        }
    
        return view('yukler.index', compact('yukler'));
    }
    /**
     * Yeni yük eklemek için form göster.
     */
    public function create()
    {
        // Sadece yük veren tipi kullanıcılar yük ekleyebilir
        if (!Auth::user()->isYukVeren()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Yük eklemek için yük veren profilinizi tamamlamanız gerekiyor.');
        }
        $ports = \App\Models\Port::orderBy('name')->get();
        return view('yukler.create', compact('ports'));
    }

    /**
     * Yeni eklenen yükü kaydet.
     */ 
    public function store(Request $request)
    {
        // Sadece yük veren tipi kullanıcılar yük ekleyebilir
        if (!Auth::user()->isYukVeren()) {
            return redirect()->route('yukler.index')
                ->with('error', 'Yük eklemek için yük veren profilinizi tamamlamanız gerekiyor.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'yuk_type' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'proposed_price' => 'required|numeric|min:0',
            'desired_delivery_date' => 'required|date',
            'description' => 'nullable|string',
            'currency' => 'required|string|max:3',
            'weight_unit' => 'required|string|max:4',
            'shipping_date' => 'nullable|date',
        ]);

        // Boyutları JSON olarak formatlama
        $dimensions = null;
        if ($request->width !== null && $request->length !== null && $request->height !== null) {
            $dimensions = [
                'width' => $request->width,
                'length' => $request->length,
                'height' => $request->height,
            ];
        }

        $yuk = Yuk::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'yuk_type' => $request->yuk_type,
            'weight' => $request->weight,
            'dimensions' => $dimensions,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'proposed_price' => $request->proposed_price,
            'desired_delivery_date' => $request->desired_delivery_date,
            'description' => $request->description,
            'status' => 'active',
            'currency' => $request->currency,
            'weight_unit' => $request->weight_unit,
            'shipping_date' => $request->shipping_date,
        ]);

        return redirect()->route('yukler.show', $yuk)
            ->with('success', 'Yük ilanı başarıyla eklendi.');
    }

    /**
     * Belirli bir yükü göster.
     */
    public function show(Yuk $yuk)
    {
        if (auth()->id() !== $yuk->user_id) {
            abort(403); // Yetkisiz erişim
        }
        $yuk->load(['matchedGemiRoute.user']);
        $startPort = \App\Models\Port::find($yuk->from_location);
        $endPort = \App\Models\Port::find($yuk->to_location);
        // Use the matching service to find compatible routes
        $matchingService = app(ShipCargoMatchingService::class);
        $matchingRoutes = app(ShipCargoMatchingService::class)->findMatchingShipsForCargo($yuk);
        $matchingRoutes = $matchingService->findMatchingShipsForCargo($yuk)
    ->reject(function ($rota) use ($yuk) {
        return $rota->id === $yuk->matched_gemi_route_id;
    });

        $muhtemelRotalar = \App\Models\GemiRoute::where('available_capacity', '>=', $yuk->weight)
        ->whereDate('departure_date', '<=', $yuk->desired_delivery_date)
        ->whereDoesntHave('matchedYukler', function ($q) use ($yuk) {
            $q->where('id', $yuk->id); // bu yük zaten eşleşmesin
        })
        ->with('user')
        ->latest()
        ->get();
        
        return view('yukler.show', compact('yuk', 'matchingRoutes','startPort','endPort', 'muhtemelRotalar'));
    }

    /**
     * Yük düzenleme formunu göster.
     */
    public function edit(Yuk $yuk)
    {
        // Sadece ilan sahibi düzenleyebilir
        if (Auth::id() !== $yuk->user_id) {
            return redirect()->route('yukler.index')
                ->with('error', 'Bu yükü düzenleme yetkiniz yok.');
        }
        $ports = \App\Models\Port::orderBy('name')->get();
        return view('yukler.edit', compact('yuk','ports'));
    }

    /**
     * Yükü güncelle.
     */
    public function update(Request $request, Yuk $yuk)
    {
        // Sadece ilan sahibi güncelleyebilir
        if (Auth::id() !== $yuk->user_id) {
            return redirect()->route('yukler.index')
                ->with('error', 'Bu yükü güncelleme yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'yuk_type' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'proposed_price' => 'required|numeric|min:0',
            'desired_delivery_date' => 'required|date',
            'description' => 'nullable|string',
            'shipping_date' => 'nullable|date',
        ]);

        // Boyutları JSON olarak formatlama
        $dimensions = null;
        if ($request->width !== null && $request->length !== null && $request->height !== null) {
            $dimensions = [
                'width' => $request->width,
                'length' => $request->length,
                'height' => $request->height,
            ];
        }

        $yuk->update([
            'title' => $request->title,
            'yuk_type' => $request->yuk_type,
            'weight' => $request->weight,
            'dimensions' => $dimensions,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'proposed_price' => $request->proposed_price,
            'desired_delivery_date' => $request->desired_delivery_date,
            'description' => $request->description,
            'shipping_date' => $request->shipping_date,
        ]);

        return redirect()->route('yukler.show', $yuk)
            ->with('success', 'Yük ilanı başarıyla güncellendi.');
    }

    /**
     * Yükü sil.
     */
    public function destroy(Yuk $yuk)
    {
        // Sadece ilan sahibi silebilir
        if (Auth::id() !== $yuk->user_id) {
            return redirect()->route('yukler.index')
                ->with('error', 'Bu yükü silme yetkiniz yok.');
        }

        $yuk->delete();

        return redirect()->route('yukler.index')
            ->with('success', 'Yük ilanı başarıyla silindi.');
    }

    /**
     * Yükü bir gemi rotasına eşleştir.
     */
    public function requestMatch(Yuk $yuk, GemiRoute $gemiRoute)
    {
    
        if (auth()->id() !== $yuk->user_id) {
            return back()->with('error', 'Bu yük için eşleştirme isteği gönderemezsiniz.');
        }
    
        if ($yuk->status !== 'active') {
            return back()->with('error', 'Bu yük zaten eşleştirilmiş.');
        }
    
        if ($yuk->weight > $gemiRoute->available_capacity) {
            return back()->with('error', 'Gemi kapasitesi yeterli değil.');
        }
    
        // This is the fixed line - only update match_status, not status
        $yuk->update([
            'matched_gemi_route_id' => $gemiRoute->id,
            'match_status' => 'pending',
            // Remove the status update to avoid conflicts
        ]);
    
        return back()->with('success', 'Eşleşme talebi gönderildi. Onay bekleniyor.');
    }
    
    /**
     * Eşleştirmeyi iptal et.
     */
    public function cancelMatch(Yuk $yuk)
{
    $yuk->load('matchedGemiRoute');

    $gemiRoute = $yuk->matchedGemiRoute;

    if (!$gemiRoute || (Auth::id() !== $yuk->user_id && Auth::id() !== $gemiRoute->user_id)) {
        return redirect()->route('yukler.show', $yuk)
            ->with('error', 'Bu eşleştirmeyi iptal etme yetkiniz yok.');
    }

    if (!in_array($yuk->match_status, ['pending', 'confirmed'])) {
        return redirect()->route('yukler.show', $yuk)
            ->with('error', 'Bu yük zaten eşleştirilmemiş.');
    }

    // Kapasiteyi geri yükle
    $gemiRoute->update([
        'available_capacity' => $gemiRoute->available_capacity + $yuk->weight,
    ]);

    // Eşleşmeyi kaldır
    $yuk->update([
        'status' => 'active',
        'matched_gemi_route_id' => null,
        'match_status' => 'Eşleşmemiş',
    ]);

    return redirect()->route('yukler.show', $yuk)
        ->with('success', 'Eşleştirme başarıyla iptal edildi.');
}

    
    /**
     * Teslimati tamamla.
     */
    public function completeDelivery(Yuk $yuk)
    {
        // Sadece yük sahibi tamamlayabilir
        if (Auth::id() !== $yuk->user_id) {
            return redirect()->route('yukler.show', $yuk)
                ->with('error', 'Bu teslimatı tamamlama yetkiniz yok.');
        }

        // Eşleştirmenin durumunu kontrol et
        if ($yuk->status !== 'matched') {
            return redirect()->route('yukler.show', $yuk)
                ->with('error', 'Bu yük henüz eşleştirilmemiş.');
        }

        // Teslimatı tamamla
        $yuk->update([
            'status' => 'completed',
        ]);

        return redirect()->route('yukler.show', $yuk)
            ->with('success', 'Teslimat başarıyla tamamlandı.');
    }
    public function markAsDelivering(Yuk $yuk)
{
    if (auth()->id() !== $yuk->user_id) {
        return back()->with('error', 'Bu işlemi yalnızca yük sahibi yapabilir.');
    }

    if ($yuk->match_status !== 'confirmed') {
        return back()->with('error', 'Teslim için eşleşme onayı gereklidir.');
    }

    $yuk->update([
        'match_status' => 'delivering',
    ]);

    return back()->with('success', 'Yük gemiye teslim edildi olarak işaretlendi.');
}
}
