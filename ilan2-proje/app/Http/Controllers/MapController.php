<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Port;
use App\Models\Ship;

class MapController extends Controller
{
    public function index()
    {
        $ports = Port::all();
        $ships = Ship::all();
        return view('map.index', compact('ports', 'ships'));
    }
}
