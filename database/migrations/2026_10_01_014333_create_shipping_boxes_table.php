<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela de embalagens reais (caixas PP, P, M, G, GG) usadas no despacho.
 *
 * Substitui a caixa fixa 54×36×27 na cotação do site
 * (Traits\Correios), na cotação do PDV (CorreiosFreightService) e na
 * pré-postagem oficial (CorreiosPrepostagemService). O PackageEstimator
 * encaixa cada pedido na MENOR caixa ativa que comporte o volume estimado.
 *
 * As medidas inseridas aqui são um ponto de partida: GG reproduz exatamente
 * a caixa antiga (para que pedidos grandes continuem cotados como antes) e
 * as demais são tamanhos comerciais comuns. Devem ser conferidas com as
 * caixas físicas do balcão e ajustadas em Admin → Correios → Embalagens.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('code', 10)->unique();
            $table->unsignedSmallInteger('length_cm');
            $table->unsignedSmallInteger('width_cm');
            $table->unsignedSmallInteger('height_cm');
            $table->unsignedInteger('max_weight_g')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        $now = now();

        DB::table('shipping_boxes')->insert([
            ['name' => 'Caixa PP', 'code' => 'PP', 'length_cm' => 16, 'width_cm' => 11, 'height_cm' => 6, 'max_weight_g' => 1000, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caixa P', 'code' => 'P', 'length_cm' => 24, 'width_cm' => 16, 'height_cm' => 10, 'max_weight_g' => 3000, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caixa M', 'code' => 'M', 'length_cm' => 30, 'width_cm' => 22, 'height_cm' => 15, 'max_weight_g' => 6000, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caixa G', 'code' => 'G', 'length_cm' => 40, 'width_cm' => 30, 'height_cm' => 25, 'max_weight_g' => 12000, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Caixa GG (jumbo)', 'code' => 'GG', 'length_cm' => 54, 'width_cm' => 36, 'height_cm' => 27, 'max_weight_g' => 30000, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_boxes');
    }
};