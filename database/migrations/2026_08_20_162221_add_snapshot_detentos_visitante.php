<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('detento_snapshot')->nullable()->after('detento_id');
            $table->json('visitante_snapshot')->nullable()->after('visitante_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['detento_snapshot', 'visitante_snapshot']);
        });
    }
};