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
        Schema::dropIfExists('visitantes');

        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 4)->nullable();
            $table->string('cep', 10)->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('prison_unit_id')->nullable();
            $table->foreign('prison_unit_id')->references('id')->on('prison_units');

          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitantes', function (Blueprint $table) {
            //
        });
    }
};
