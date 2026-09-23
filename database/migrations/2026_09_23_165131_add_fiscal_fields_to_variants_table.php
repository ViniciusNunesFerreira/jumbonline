<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->string('ncm', 8)->nullable()->after('barcode');
            $table->string('cfop', 4)->nullable()->after('ncm');
            $table->unsignedTinyInteger('origin')->nullable()->after('cfop');
        });
    }

    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn(['ncm', 'cfop', 'origin']);
        });
    }
};