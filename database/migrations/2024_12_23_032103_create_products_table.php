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
            $table->string('name');
            $table->string('name_full');
            $table->bigInteger('price');
            $table->tinyInteger('sale');
            $table->longText('description');
            $table->tinyInteger('status')->comment('1:active | 2:inactive');
            $table->tinyInteger('hot')->comment('1: product is hot | 2: product is not hot');
            $table->longText('content');
            $table->string('image');
            $table->integer('qty_pay');
            $table->integer('quantity');
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
