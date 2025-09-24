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
        Schema::create('siram_configs', function (Blueprint $table) {
            $table->id('siram_id');
            $table->foreignId('device_id')->constrained();
            $table->decimal('upper_threshold', total: 4, places: 2)->default(80, 00);
            $table->decimal('lower_threshold', total: 4, places: 2)->default(60, 00);
            $table->enum('mode', ['Auto', 'On', 'Off'])->default('Auto');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('device_id');
        });

        // numpang
        Schema::table('humida_configs', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siram_configs');
    }
};
