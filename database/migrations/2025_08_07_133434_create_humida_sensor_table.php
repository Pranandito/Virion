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
        Schema::create('humida_sensors', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('device_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('temperature', total: 4, places: 2);
            $table->decimal('humidity', total: 4, places: 2);
            $table->time('online_duration');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('humida_sensor');
    }
};
