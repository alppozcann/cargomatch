<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plate_code')->unique();
            $table->string('ship_name')->nullable();
            $table->string('ship_type')->nullable();
            $table->float('carrying_capacity')->nullable();
            $table->json('load_types')->nullable(); // taşıyabileceği yük türleri
            $table->json('certificates')->nullable(); // sahip olduğu belgeler
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ships');
    }
};
