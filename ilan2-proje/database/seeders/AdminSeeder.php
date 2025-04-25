<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user already exists
        if (!User::where('email', 'alp@alp.com')->exists()) {
            // Admin kullanıcısı oluştur
            User::create([
                'name' => 'Admin',
                'email' => 'alp@alp.com',
                'password' => Hash::make('NSprinta'),
                'phone_number' => '05393899424',
                'is_admin' => true,
            ]);

            $this->command->info('Admin kullanıcısı oluşturuldu!');
        } else {
            $this->command->info('Admin kullanıcısı zaten mevcut!');
        }
    }
}
