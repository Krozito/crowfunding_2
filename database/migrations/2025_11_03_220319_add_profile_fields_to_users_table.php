<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Nuevo nombre en español (mantén 'name' para compatibilidad con Breeze)
            $table->string('nombre_completo')->nullable()->after('name');

            // Campos de perfil / verificación
            $table->boolean('estado_verificacion')->default(false)->after('email')->index();
            $table->text('info_personal')->nullable()->after('estado_verificacion');
            $table->json('redes_sociales')->nullable()->after('info_personal');
            $table->unsignedTinyInteger('indice_confianza')->default(0)->after('redes_sociales');
        });

        // Backfill simple: si existe 'name' y 'nombre_completo' está vacío, copia
        \DB::table('users')
          ->whereNull('nombre_completo')
          ->update(['nombre_completo' => \DB::raw('name')]);
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre_completo','estado_verificacion','info_personal','redes_sociales','indice_confianza']);
        });
    }
};
