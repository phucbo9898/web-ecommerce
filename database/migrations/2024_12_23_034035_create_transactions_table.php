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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('customer_name');
            $table->bigInteger('total_amount');
            $table->string('note');
            $table->string('address');
            $table->string('phone');
            $table->tinyInteger('status')->comment('1: pending | 2: processing | 3: completed | 4: canceled');
            $table->tinyInteger('type_payment')->comment('1: vnpay | 2: momo | 3: normal');
            $table->tinyInteger('status_payment')->comment('1: payment received | 2: payment not received');
            $table->string('payment_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
