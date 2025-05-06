<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->bigInteger('price')->change(); // veya decimal(...)
        });
    }
    
    public function down()
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->integer('price')->change(); // eski haline dön
        });
    }
};
