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
        Schema::create('feed_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->decimal('feed_size', total: 3, places: 1)->default(1.0);
            $table->timestamp('last_refill')->nullable();
            $table->enum('mode', ['Auto', 'On', 'Off'])->default('Auto');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_configs');
    }
};
