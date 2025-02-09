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
            $table->id();
            $table->foreignId('rol_id')->constrained('rol');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nickname');
            $table->string('email')->unique();
            $table->decimal('saldo')->default(1000);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->rememberToken()->nullable();
            $table->string('estado_registro')->default('A');
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
