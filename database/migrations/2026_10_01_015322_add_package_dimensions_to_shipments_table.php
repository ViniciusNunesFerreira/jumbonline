<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Registra no shipment a embalagem efetivamente informada aos Correios na
 * pré-postagem (dimensões + peso) e um snapshot da estimativa original do
 * sistema, para auditoria "estimado × real" e futura calibração da
 * densidade média do catálogo.
 *
 * shipping_box_id usa nullOnDelete: excluir uma caixa do cadastro nunca
 * apaga nem quebra o histórico — as medidas ficam gravadas nas colunas
 * package_* do próprio shipment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreignId('shipping_box_id')->nullable()->after('correios_destinatario_manual')->constrained('shipping_boxes')->nullOnDelete();
            $table->unsignedSmallInteger('package_length_cm')->nullable()->after('shipping_box_id');
            $table->unsignedSmallInteger('package_width_cm')->nullable()->after('package_length_cm');
            $table->unsignedSmallInteger('package_height_cm')->nullable()->after('package_width_cm');
            $table->unsignedInteger('package_weight_g')->nullable()->after('package_height_cm');
            $table->string('package_source', 20)->nullable()->after('package_weight_g');
            $table->json('package_estimate')->nullable()->after('package_source');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_box_id');
            $table->dropColumn([
                'package_length_cm',
                'package_width_cm',
                'package_height_cm',
                'package_weight_g',
                'package_source',
                'package_estimate',
            ]);
        });
    }
};