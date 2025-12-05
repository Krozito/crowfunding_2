<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'profesion')) {
                $table->string('profesion', 120)->nullable()->after('nombre_completo');
            }
            if (!Schema::hasColumn('users', 'experiencia')) {
                $table->text('experiencia')->nullable()->after('profesion');
            }
            if (!Schema::hasColumn('users', 'biografia')) {
                $table->text('biografia')->nullable()->after('experiencia');
            }
            if (!Schema::hasColumn('users', 'foto_perfil')) {
                $table->string('foto_perfil', 255)->nullable()->after('biografia');
            }
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'foto_perfil')) {
                $table->dropColumn('foto_perfil');
            }
            if (Schema::hasColumn('users', 'biografia')) {
                $table->dropColumn('biografia');
            }
            if (Schema::hasColumn('users', 'experiencia')) {
                $table->dropColumn('experiencia');
            }
            if (Schema::hasColumn('users', 'profesion')) {
                $table->dropColumn('profesion');
            }
        });
    }
};
