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
        Schema::create('nota_fiscal', function (Blueprint $table) {
            $table->id();
            $table->date('data_pagamento');
            $table->float('valor')->nullable();
            $table->date('data_liquidacao')->nullable();
            $table->float('liquidacao')->nullable();
            $table->string('nfe')->nullable();
            $table->float('ordem_servico')->nullable();
            $table->string('observacao')->nullable();
            $table->string('ci')->nullable();
            $table->unsignedBigInteger('empenho_id')->nullable();
            $table->foreign('empenho_id')->references('id')->on('empenho');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nota_fiscal');
    }
};
