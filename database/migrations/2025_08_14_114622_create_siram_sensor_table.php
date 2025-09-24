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
        Schema::create('siram_sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->decimal('temperature', 4, 2);
            $table->decimal('humidity', 4, 2);
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
        Schema::dropIfExists('siram_sensor');
    }
};
