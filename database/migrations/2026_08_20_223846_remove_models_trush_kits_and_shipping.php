<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_shipping_method_foreign');
            $table->dropColumn('shipping_method');
        });

        // Kit legado
        Schema::dropIfExists('kit_product');
        Schema::dropIfExists('kits');

        // Zonas de frete
        Schema::dropIfExists('shipping_zone_countries');
        Schema::dropIfExists('shipping_zone_rates');
        Schema::dropIfExists('shipping_zones');

        // Zonas de imposto
        Schema::dropIfExists('tax_zone_countries');
        Schema::dropIfExists('tax_zone_rates');
        Schema::dropIfExists('tax_zones');
    }

    public function down(): void
    {
        // Reversão via backup do staging, não automática — são 8 tabelas.
    }
};