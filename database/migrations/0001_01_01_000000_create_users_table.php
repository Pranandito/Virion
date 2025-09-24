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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('username', 20);
            $table->string('email', 73)->unique();
            $table->string('profile_pict');
            $table->string('password');
            $table->string('nomor_hp', 15);
            $table->enum('job', ['Petani', 'Peternak', 'Pembudidaya']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
