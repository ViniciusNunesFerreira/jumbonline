<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->json('correios_remetente_manual')->nullable()->after('correios_label_recibo');
            $table->json('correios_destinatario_manual')->nullable()->after('correios_remetente_manual');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['correios_remetente_manual', 'correios_destinatario_manual']);
        });
    }
};