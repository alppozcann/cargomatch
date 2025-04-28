<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->foreignId('ship_id')->nullable()->constrained('ships')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->dropForeign(['ship_id']);
            $table->dropColumn('ship_id');
        });
    }
};
