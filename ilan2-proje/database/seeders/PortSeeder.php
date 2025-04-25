<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run()
    {
        $ports = [
            ['name' => 'İstanbul Limanı', 'latitude' => 41.0369, 'longitude' => 28.9862],
            ['name' => 'İzmir Limanı', 'latitude' => 38.4192, 'longitude' => 27.1287],
            ['name' => 'Mersin Limanı', 'latitude' => 36.7978, 'longitude' => 34.6415],
            ['name' => 'Trabzon Limanı', 'latitude' => 41.0053, 'longitude' => 39.7225],
            ['name' => 'Antalya Limanı', 'latitude' => 36.8527, 'longitude' => 30.7850],
            ['name' => 'Bandırma Limanı', 'latitude' => 40.3521, 'longitude' => 27.9724],
            ['name' => 'Samsun Limanı', 'latitude' => 41.2867, 'longitude' => 36.33],
        ];

        foreach ($ports as $port) {
            Port::create($port);
        }
    }
}
