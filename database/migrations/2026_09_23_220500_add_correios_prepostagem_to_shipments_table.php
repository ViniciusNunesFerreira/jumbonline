<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('correios_prepostagem_id')->nullable()->after('cost');
            $table->string('correios_status')->nullable()->after('correios_prepostagem_id');
            $table->string('correios_label_recibo')->nullable()->after('correios_status');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['correios_prepostagem_id', 'correios_status', 'correios_label_recibo']);
        });
    }
};