@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Harita</h2>
    <div id="map" style="height: 600px;"></div>
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([38.5, 27.0], 6); // Türkiye merkezli

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    // Limanları yerleştir
    @foreach($ports as $port)
        L.marker([{{ $port->latitude }}, {{ $port->longitude }}])
            .addTo(map)
            .bindPopup("<strong>{{ $port->name }}</strong><br> (Liman)");
    @endforeach

    // Gemileri yerleştir
    @foreach($ships as $ship)
        L.marker([{{ $ship->current_latitude }}, {{ $ship->current_longitude }}], {icon: L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854878.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
        })})
            .addTo(map)
            .bindPopup("<strong>{{ $ship->name }}</strong><br>Status: {{ $ship->status }}");
    @endforeach
});
</script>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([38.5, 27.0], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    // Liman markerları
    @foreach($ports as $port)
        L.marker([{{ $port->latitude }}, {{ $port->longitude }}])
            .addTo(map)
            .bindPopup("<strong>{{ $port->name }}</strong><br> (Liman)");
    @endforeach

    // Gemiler için boş obje
    var shipMarkers = {};

    function loadShips() {
        fetch('/api/ships')
            .then(response => response.json())
            .then(data => {
                data.forEach(ship => {
                    // Eğer gemi zaten varsa, yerini güncelle
                    if (shipMarkers[ship.id]) {
                        shipMarkers[ship.id].setLatLng([ship.current_latitude, ship.current_longitude]);
                    } else {
                        // Yeni gemi için marker oluştur
                        var icon = L.icon({
                            iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854878.png',
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                        });

                        shipMarkers[ship.id] = L.marker([ship.current_latitude, ship.current_longitude], {icon: icon})
                            .addTo(map)
                            .bindPopup("<strong>" + ship.name + "</strong><br>Status: " + ship.status);
                    }
                });
            });
    }

    loadShips(); // ilk yükleme
    setInterval(loadShips, 5000); // her 5 saniyede bir güncelle
});
</script>
