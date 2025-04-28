<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipController extends Controller
{
    public function index()
    {
        $ships = Ship::where('user_id', Auth::id())->latest()->get();
        return view('ships.index', compact('ships'));
    }

    public function create()
    {
        $cargoTypes = [
            'Konteyner Yükleri',
            'Dökme Kuru Yükler',
            'Sıvı Yükler (Tanker Yükleri)',
            'Soğutmalı Yükler (Reefer Yükleri)',
            'Araç Yükleri (Ro-Ro Yükleri)',
            'Proje ve Ağır Yükler',
            'Tehlikeli Maddeler',
            'Canlı Hayvanlar',
            'Ağaç ve Orman Ürünleri',
            'Genel Kargo (Break Bulk)',
        ];
        return view('ships.create', compact('cargoTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_code' => 'required|string|max:255|unique:ships,plate_code',
            'ship_name' => 'nullable|string|max:255',
            'ship_type' => 'nullable|string|max:255',
            'carrying_capacity' => 'nullable|numeric|min:0',
            'load_types' => 'nullable|array',
            'certificates' => 'nullable|array',
        ]);
    
        $ship = Ship::create([
            'user_id' => Auth::id(),
            'plate_code' => $validated['plate_code'],
            'ship_name' => $validated['ship_name'] ?? null,
            'ship_type' => $validated['ship_type'] ?? null,
            'carrying_capacity' => $validated['carrying_capacity'] ?? null,
            'load_types' => $validated['load_types'] ?? [],
            'certificates' => $validated['certificates'] ?? [],
        ]);
    
        return redirect()->route('ships.index')->with('success', 'Gemi başarıyla eklendi.');
    }
    

    public function edit(Ship $ship)
    {
        if ($ship->user_id !== Auth::id()) {
            abort(403);
        }

        $cargoTypes = [
            'Konteyner Yükleri',
            'Dökme Kuru Yükler',
            'Sıvı Yükler (Tanker Yükleri)',
            'Soğutmalı Yükler (Reefer Yükleri)',
            'Araç Yükleri (Ro-Ro Yükleri)',
            'Proje ve Ağır Yükler',
            'Tehlikeli Maddeler',
            'Canlı Hayvanlar',
            'Ağaç ve Orman Ürünleri',
            'Genel Kargo (Break Bulk)',
        ];
    
        return view('ships.edit', compact('ship', 'cargoTypes'));
    }

    public function update(Request $request, Ship $ship)
    {
        if ($ship->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'plate_code' => 'required|string|max:255|unique:ships,plate_code,' . $ship->id,
            'ship_name' => 'required|string|max:255',
            'ship_type' => 'nullable|string|max:255',
            'carrying_capacity' => 'nullable|numeric|min:0',
            'load_types' => 'nullable|array',
            'load_types.*' => 'string|max:255',
            'certificates' => 'nullable|array',
            'certificates.*' => 'string|max:255',
        ]);

        $ship->update([
            'plate_code' => $validated['plate_code'],
            'ship_name' => $validated['ship_name'],
            'ship_type' => $validated['ship_type'],
            'carrying_capacity' => $validated['carrying_capacity'],
            'load_types' => $validated['load_types'] ?? [],
            'certificates' => $validated['certificates'] ?? [],
        ]);

        return redirect()->route('ships.index')->with('success', 'Gemi başarıyla güncellendi.');
    }

    public function destroy(Ship $ship)
    {
        if ($ship->user_id !== Auth::id()) {
            abort(403);
        }

        $ship->delete();
        return redirect()->route('ships.index')->with('success', 'Gemi başarıyla silindi.');
    }
}
