<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('actualizaciones_proyecto', function (Blueprint $table) {
            $table->boolean('es_hito')->default(false)->after('contenido');
            $table->json('adjuntos')->nullable()->after('es_hito');
        });
    }

    public function down(): void {
        Schema::table('actualizaciones_proyecto', function (Blueprint $table) {
            $table->dropColumn(['es_hito', 'adjuntos']);
        });
    }
};
