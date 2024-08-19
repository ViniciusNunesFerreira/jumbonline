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
        Schema::create('collection_prison_unit', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Collection::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\PrisonUnit::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_prison_unit', function (Blueprint $table) {
            //
        });
    }
};
