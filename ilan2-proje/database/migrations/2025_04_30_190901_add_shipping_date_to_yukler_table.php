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
            $table->date('shipping_date')->nullable()->after('desired_delivery_date');
        });
    }
    
    public function down()
    {
        Schema::table('yuks', function (Blueprint $table) {
            $table->dropColumn('shipping_date');
        });
    }
};
