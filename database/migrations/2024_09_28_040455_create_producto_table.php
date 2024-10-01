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
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categoria_producto');
            $table->string('nombre');
            $table->string('descripcion');
            $table->decimal('precio');
            $table->integer('calificacion_final')->default(0);
            $table->integer('stock')->default(0);
            $table->string('producto_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
