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
        Schema::table('feed_configs', function (Blueprint $table) {
            $table->integer('manual_daily')->after('success_weekly')->default(0);
            $table->integer('manual_weekly')->after('manual_daily')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_configs', function (Blueprint $table) {
            $table->dropColumn('manual_daily');
            $table->dropColumn('manual_weekly');
        });
    }
};
