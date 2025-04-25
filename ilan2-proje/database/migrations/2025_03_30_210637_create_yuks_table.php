<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // İlan başlığı
            $table->string('yuk_type'); // Yük türü
            $table->decimal('weight', 10, 2); // Ağırlık (kg)
            $table->json('dimensions')->nullable(); // Boyutlar
            $table->string('from_location'); // Yükün alınacağı yer
            $table->string('to_location'); // Yükün teslim edileceği yer
            $table->decimal('proposed_price', 10, 2); // Teklif edilen fiyat
            $table->dateTime('desired_delivery_date'); // İstenen teslimat tarihi
            $table->text('description')->nullable(); // Detaylı açıklama
            $table->string('status')->default('active'); // İlan durumu
            $table->foreignId('matched_gemi_route_id')->nullable()->constrained('gemi_routes')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yuks');
    }
}
