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
        Schema::create('pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('exercicio');
            $table->string('termo_de_referencia');
            $table->date('data_vigencia');
            $table->float('valor_empenho');
            $table->float('valor_liquidacao');
            $table->float('valor_total_pago');
            $table->bigInteger('empenho');
            $table->string('observacao');
            $table->unsignedBigInteger('termo_id');
            $table->foreign('termo_id')->references('id')->on('termo');
            $table->unsignedBigInteger('termo_aditivo_id');
            $table->foreign('termo_aditivo_id')->references('id')->on('termo_aditivo');
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
        //
    }
};
