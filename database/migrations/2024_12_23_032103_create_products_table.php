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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->bigInteger('price')->nullable();
            $table->tinyInteger('sale')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('publish')->comment('1: published | 2: not published');
            $table->tinyInteger('hot')->comment('1: product is hot | 2: product is not hot');
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->integer('qty_pay')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('total_star')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
