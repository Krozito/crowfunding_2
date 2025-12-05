<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedor_historiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
            $table->string('concepto');
            $table->decimal('monto', 15, 2)->default(0);
            $table->date('fecha_entrega')->nullable();
            $table->unsignedTinyInteger('calificacion')->nullable(); // 1..10
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proveedor_historiales');
    }
};
