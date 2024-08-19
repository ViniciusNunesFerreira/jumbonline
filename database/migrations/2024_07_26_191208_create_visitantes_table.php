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
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('logradouro');
            $table->string('numero', 10);
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf', 4);
            $table->string('cep', 10);
            $table->string('cod_consultor')->nullable();
            $table->text('doc')->charset('binary');
            $table->foreignIdFor(\App\Models\Customer::class)->constrained();
            $table->foreignIdFor(\App\Models\PrisonUnit::class)->constrained()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
