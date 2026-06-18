<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Cria a coluna definindo 'site' como padrão absoluto para os produtos antigos
            $table->enum('sales_channel', ['site', 'balcao', 'ambos'])
                  ->default('site')
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sales_channel');
        });
    }
};