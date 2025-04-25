<?php

namespace Database\Seeders;

use App\Models\Yuk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class YukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a yuk_veren user
        $yukVeren = User::where('user_type', 'yuk_veren')->first();
        
        if (!$yukVeren) {
            // Create a yuk_veren user if none exists
            $yukVeren = User::create([
                'name' => 'Test Yük Veren',
                'email' => 'yuk_veren@test.com',
                'password' => bcrypt('password'),
                'user_type' => 'yuk_veren',
                'phone_number' => '5559876543',
                'company_name' => 'Test Cargo Co.',
                'company_address' => 'Test Address',
            ]);
        }

        // Create test cargo
        Yuk::create([
            'title' => 'Test Yükü',
            'yuk_type' => 'Konteyner',
            'from_location' => 'Antalya',
            'to_location' => 'İzmir',
            'weight' => 10000,
            'weight_unit' => 'kg',
            'proposed_price' => 50000,
            'currency' => 'TRY',
            'desired_delivery_date' => Carbon::parse('2025-04-18'),
            'status' => 'active',
            'user_id' => $yukVeren->id,
        ]);
    }
} 