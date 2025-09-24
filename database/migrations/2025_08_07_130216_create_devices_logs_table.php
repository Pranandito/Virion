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
        Schema::create('devices_logs', function(Blueprint $table){
            $table->id('log_id');
            $table->foreignId('device_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->index('device_id');
            $table->string('activity', 120);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices_logs');
    }
};
