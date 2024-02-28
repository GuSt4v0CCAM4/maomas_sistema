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
        Schema::create('expense_others', function (Blueprint $table) {
            $table->unsignedBigInteger('id_expense');
            $table->foreign('id_expense')->references('id_cash')->on('expenses');
            $table->string('details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_others');
    }
};
