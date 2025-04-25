<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('yuks', function (Blueprint $table) {
            $table->string('match_status')->default('pending')->after('status');
            $table->text('match_notes')->nullable()->after('match_status');
            $table->timestamp('matched_at')->nullable()->after('match_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yuks', function (Blueprint $table) {
            $table->dropColumn(['match_status', 'match_notes', 'matched_at']);
        });
    }
};
