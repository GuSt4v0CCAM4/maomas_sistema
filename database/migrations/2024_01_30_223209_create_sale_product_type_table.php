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
        Schema::create('sale_product_type', function (Blueprint $table) {
            $table->id('id_reg');
            $table->unsignedBigInteger('id_sale');
            $table->foreign('id_sale')->references('id_reg')->on('sales_amount');
            $table->integer('product');
            $table->double('amount');
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id')->on('stores');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_product_type');
    }
};
