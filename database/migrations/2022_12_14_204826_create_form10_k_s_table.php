<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form10_k_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cik_id')->constrained('ciks');
            $table->string('filling_date');
            $table->string('operations');
            $table->string('income');
            $table->string('cash_flows');
            $table->string('balance_sheet');
            $table->string('shareholders_equity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form10_k_s');
    }
};
