<?php

namespace Database\Seeders;

use App\Models\GemiRoute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GemiRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a gemici user
        $gemici = User::where('user_type', 'gemici')->first();
        
        if (!$gemici) {
            // Create a gemici user if none exists
            $gemici = User::create([
                'name' => 'Test Gemici',
                'email' => 'gemici@test.com',
                'password' => bcrypt('password'),
                'user_type' => 'gemici',
                'phone_number' => '5551234567',
                'company_name' => 'Test Shipping Co.',
                'company_address' => 'Test Address',
            ]);
        }

        // Create test routes
        $routes = [
            [
                'title' => 'İstanbul - İzmir Konteyner Rotası',
                'start_location' => 'İstanbul',
                'end_location' => 'İzmir',
                'way_points' => ['Çanakkale', 'Bodrum'],
                'available_capacity' => 25000.00,
                'price' => 150000.00,
                'departure_date' => Carbon::now()->addDays(5),
                'arrival_date' => Carbon::now()->addDays(8),
                'description' => 'İstanbul\'dan İzmir\'e konteyner taşıma rotası. Çanakkale ve Bodrum\'da duraklama yapılacak.',
            ],
            [
                'title' => 'Mersin - Antalya Dökme Yük Rotası',
                'start_location' => 'Mersin',
                'end_location' => 'Antalya',
                'way_points' => ['Alanya'],
                'available_capacity' => 15000.00,
                'price' => 75000.00,
                'departure_date' => Carbon::now()->addDays(3),
                'arrival_date' => Carbon::now()->addDays(5),
                'description' => 'Mersin\'den Antalya\'ya dökme yük taşıma rotası. Alanya\'da yükleme yapılacak.',
            ],
            [
                'title' => 'Trabzon - Samsun Proje Yükü Rotası',
                'start_location' => 'Trabzon',
                'end_location' => 'Samsun',
                'way_points' => ['Ordu', 'Giresun'],
                'available_capacity' => 5000.00,
                'price' => 45000.00,
                'departure_date' => Carbon::now()->addDays(2),
                'arrival_date' => Carbon::now()->addDays(4),
                'description' => 'Trabzon\'dan Samsun\'a proje yükü taşıma rotası. Ordu ve Giresun\'da duraklama yapılacak.',
            ],
            [
                'title' => 'Mersin - İstanbul Ara Duraklı Rota',
                'start_location' => 'Mersin',
                'end_location' => 'İstanbul',
                'way_points' => ['Antalya', 'İzmir'],
                'available_capacity' => 20000.00,
                'price' => 100000.00,
                'departure_date' => Carbon::parse('2025-04-14'),
                'arrival_date' => Carbon::parse('2025-04-20'),
                'description' => 'Mersin\'den İstanbul\'a konteyner taşıma rotası. Antalya ve İzmir\'de duraklama yapılacak.',
            ],
        ];

        foreach ($routes as $route) {
            GemiRoute::create(array_merge($route, [
                'user_id' => $gemici->id,
                'status' => 'active',
            ]));
        }
    }
} 