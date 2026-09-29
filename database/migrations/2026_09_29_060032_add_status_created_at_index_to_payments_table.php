<?php

// database/migrations/2026_09_29_090100_add_status_created_at_index_to_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Suporta o alerta de "pedido parado" (StalledOrderService), que filtra
 * orders via whereHas('paidPayments', status=PAID AND created_at <= cutoff).
 * Operação apenas aditiva: não altera dados.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'payments_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_status_created_at_index');
        });
    }
};