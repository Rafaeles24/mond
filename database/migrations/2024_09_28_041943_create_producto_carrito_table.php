<?php

use Carbon\Carbon;
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
        Schema::create('producto_carrito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('producto');
            $table->foreignId('carrito_id')->constrained('carrito');
            $table->integer('cantidad');
            $table->date('fecha_agregado')->default(Carbon::now()->format('Y-m-d'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_carrito');
    }
};
