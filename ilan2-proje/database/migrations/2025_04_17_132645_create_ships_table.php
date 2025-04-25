<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('current_latitude', 10, 6)->nullable();
            $table->decimal('current_longitude', 10, 6)->nullable();
            $table->string('status')->default('yolda'); // yolda, limanda vs.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ships');
    }
}