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
        // Schema::table('feed_configs', function (Blueprint $table) {
        //     $table->integer('total_daily')->after('mode');
        //     $table->integer('total_weekly')->after('total_daily');
        //     $table->integer('success_daily')->after('total_weekly');
        //     $table->integer('success_weekly')->after('success_daily');
        //     $table->timestamp('last_feed')->after('success_weekly')->nullable();
        // });

        Schema::table('feed_schedules', function (Blueprint $table) {
            $table->boolean('active_status')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_configs', function (Blueprint $table) {
            $table->dropColumn('total_daily', 'total_weekly', 'success_daily', 'success_weekly', 'last_feed');
        });
    }
};
