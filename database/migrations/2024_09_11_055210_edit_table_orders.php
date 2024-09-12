<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_label')->after('shipping_status')->nullable();
            $table->foreignIdFor(\App\Models\PrisonUnit::class)->after('customer_id')->constrained();

            $table->unsignedBigInteger('visitante_id')->after('customer_id')->nullable();
            $table->foreign('visitante_id')->references('id')->on('visitantes');

            $table->unsignedBigInteger('detento_id')->after('visitante_id')->nullable();
            $table->foreign('detento_id')->references('id')->on('detentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
        });
    }
};
