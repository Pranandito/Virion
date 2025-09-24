<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feed_storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->decimal('storage', total: 4, places: 2);
            $table->time('online_duration');
            $table->timestamp('created_at')->useCurrent();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_storages');
    }
};
