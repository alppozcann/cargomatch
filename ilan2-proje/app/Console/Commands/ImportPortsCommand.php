<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Port;
use SimpleXMLElement;

class ImportPortsCommand extends Command
{
    protected $signature = 'ports:import {path}';

    protected $description = 'KML dosyasından limanları içeri aktarır';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("Dosya bulunamadı: $path");
            return 1;
        }

        $xml = simplexml_load_file($path);
        $xml->registerXPathNamespace('kml', 'http://www.opengis.net/kml/2.2');
        $placemarks = $xml->xpath('//kml:Placemark');

        $count = 0;
        foreach ($placemarks as $p) {
            $name = (string) $p->name;
        
            // $p'nin içinden değil, doğrudan simple nesnesinin Point > coordinates altını manuel oku
            $point = $p->Point;
            if (!$point) {
                $this->warn("Point bulunamadı: $name");
                continue;
            }
        
            $coords = trim((string) $point->coordinates);
        
            if (empty($coords)) {
                $this->warn("Koordinatlar eksik atlandı: $name");
                continue;
            }
        
            $coordParts = explode(',', $coords);
        
            if (count($coordParts) < 2) {
                $this->warn("Koordinatlar eksik atlandı: $name → $coords");
                continue;
            }
        
            [$lon, $lat] = array_map('floatval', $coordParts);
        
            Port::updateOrCreate(
                ['name' => $name],
                ['latitude' => $lat, 'longitude' => $lon]
            );
        
            $count++;
        }
        

        $this->info("Toplam $count liman başarıyla eklendi/güncellendi.");
        return 0;
    }
}
