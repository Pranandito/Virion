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
        Schema::create('humida_configs', function (Blueprint $table) {
            $table->id('humida_id');
            $table->foreignId('device_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('upper_threshold', total: 4, places: 2)->default(70, 00);
            $table->decimal('lower_threshold', total: 4, places: 2)->default(50, 00);
            $table->enum('mode', ['Auto', 'On', 'Off'])->default('Auto');
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('humida_configs');
    }
};
