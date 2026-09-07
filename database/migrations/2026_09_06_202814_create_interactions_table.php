<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Employee::class)->nullable()->constrained()->nullOnDelete();
            $table->string('channel');
            $table->string('type');
            $table->text('description');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};