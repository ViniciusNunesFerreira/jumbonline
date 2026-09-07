<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Receita líquida acumulada (pagamentos PAID - reembolsos).
            $table->decimal('ltv_total', 12, 2)->default(0)->after('notes');

            $table->unsignedInteger('paid_orders_count')->default(0)->after('ltv_total');

            $table->timestamp('last_order_at')->nullable()->after('paid_orders_count');

            $table->index('ltv_total');
            $table->index('last_order_at');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['customers_ltv_total_index']);
            $table->dropIndex(['customers_last_order_at_index']);
            $table->dropColumn(['ltv_total', 'paid_orders_count', 'last_order_at']);
        });
    }
};
