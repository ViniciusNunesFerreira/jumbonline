<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->index('session_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('shipping_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['session_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['shipping_status']);
            $table->dropIndex(['created_at']);
        });
    }
};