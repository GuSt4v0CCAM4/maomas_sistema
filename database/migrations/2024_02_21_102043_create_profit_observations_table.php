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
        Schema::create('profit_observations', function (Blueprint $table) {
            $table->unsignedBigInteger('id_profit');
            $table->foreign('id_profit')->references('id_reg')->on('profits');
            $table->string('observation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_observations');
    }
};
