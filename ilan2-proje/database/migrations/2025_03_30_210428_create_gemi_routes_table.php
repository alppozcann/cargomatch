<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGemiRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gemi_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // İlan başlığı
            $table->string('start_location'); // Başlangıç noktası
            $table->string('end_location'); // Bitiş noktası
            $table->json('way_points')->nullable(); // Ara duraklar
            $table->decimal('available_capacity', 10, 2)->nullable(false); // Boş kapasite (kg)
            $table->decimal('price', 10, 2); // İstenen ücret
            $table->dateTime('departure_date'); // Hareket tarihi
            $table->dateTime('arrival_date'); // Varış tarihi
            $table->text('description')->nullable(); // Detaylı açıklama
            $table->string('status')->default('active'); // İlan durumu
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
        Schema::dropIfExists('gemi_routes');
    }
}
