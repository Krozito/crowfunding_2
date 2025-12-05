<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->string('imagen_portada')->nullable()->after('ubicacion_geografica');
        });
    }

    public function down(): void {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('imagen_portada');
        });
    }
};
