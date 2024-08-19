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
        Schema::create('detentos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('matricula');
            $table->string('raio');
            $table->string('cela');
            $table->foreignIdFor(\App\Models\PrisonUnit::class)->constrained()->nullable();
            $table->foreignIdFor(\App\Models\Customer::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detentos');
    }
};
