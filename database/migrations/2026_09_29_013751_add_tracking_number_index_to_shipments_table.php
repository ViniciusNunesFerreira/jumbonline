<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Índice para a busca ampliada de pedidos por código de rastreio.
 *
 * A busca usa igualdade (código completo) ou LIKE por prefixo ("AB1234%"),
 * que podem aproveitar este índice. Operação apenas aditiva: não altera dados.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->index('tracking_number', 'shipments_tracking_number_index');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex('shipments_tracking_number_index');
        });
    }
};