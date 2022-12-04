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
        Schema::create('financial_industries', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('sector_id');
            // $table->foreign('sector_id')->references('id')->on('financial_sectors');
            $table->foreignId('sector_id')->constrained('financial_sectors');
            $table->integer('sic_code');
            $table->text('industry');
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
        Schema::dropIfExists('financial_industries');
    }
};
