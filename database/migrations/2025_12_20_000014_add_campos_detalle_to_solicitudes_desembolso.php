<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('solicitudes_desembolso', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitudes_desembolso', 'hito')) {
                $table->string('hito', 160)->nullable()->after('fecha_solicitud');
            }
            if (!Schema::hasColumn('solicitudes_desembolso', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('hito');
            }
            if (!Schema::hasColumn('solicitudes_desembolso', 'proveedores')) {
                $table->json('proveedores')->nullable()->after('descripcion');
            }
            if (!Schema::hasColumn('solicitudes_desembolso', 'fecha_estimada')) {
                $table->date('fecha_estimada')->nullable()->after('proveedores');
            }
            if (!Schema::hasColumn('solicitudes_desembolso', 'adjuntos')) {
                $table->json('adjuntos')->nullable()->after('fecha_estimada');
            }
        });
    }

    public function down(): void {
        Schema::table('solicitudes_desembolso', function (Blueprint $table) {
            if (Schema::hasColumn('solicitudes_desembolso', 'adjuntos')) {
                $table->dropColumn('adjuntos');
            }
            if (Schema::hasColumn('solicitudes_desembolso', 'fecha_estimada')) {
                $table->dropColumn('fecha_estimada');
            }
            if (Schema::hasColumn('solicitudes_desembolso', 'proveedores')) {
                $table->dropColumn('proveedores');
            }
            if (Schema::hasColumn('solicitudes_desembolso', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('solicitudes_desembolso', 'hito')) {
                $table->dropColumn('hito');
            }
        });
    }
};
