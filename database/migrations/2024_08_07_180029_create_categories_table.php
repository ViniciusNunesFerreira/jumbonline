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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->integer('quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('seo_title', 70)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('category_collection', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Collection::class)->constrained()->cascadeOnDelete();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Product::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
