<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'adjuntos')) {
                $table->json('adjuntos')->nullable()->after('concepto');
            }
        });
    }

    public function down(): void {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'adjuntos')) {
                $table->dropColumn('adjuntos');
            }
        });
    }
};
