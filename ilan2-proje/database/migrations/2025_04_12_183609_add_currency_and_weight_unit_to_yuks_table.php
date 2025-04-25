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
        Schema::table('yuks', function (Blueprint $table) {
            if (!Schema::hasColumn('yuks', 'currency')) {
                $table->string('currency')->default('TRY');
            }
            if (!Schema::hasColumn('yuks', 'weight_unit')) {
                $table->string('weight_unit')->default('kg');
            }
        });
    }
    
    public function down()
    {
        Schema::table('yuks', function (Blueprint $table) {
            $table->dropColumn(['currency', 'weight_unit']);
        });
    }
};
    
