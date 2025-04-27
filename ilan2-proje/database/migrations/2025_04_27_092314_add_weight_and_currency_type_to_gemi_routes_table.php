<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->string('weight_type')->nullable();
            $table->string('currency_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gemi_routes', function (Blueprint $table) {
            $table->dropColumn(['weight_type', 'currency_type']);
        });
    }
};
