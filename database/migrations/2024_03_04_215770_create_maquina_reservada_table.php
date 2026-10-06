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
        Schema::create('maquina_reservadas', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('colaborador_area_id');
            $table->foreign('colaborador_area_id')->references('id')->on('colaboradores_por__areas');
            $table->unSignedBigInteger('maquina_id');
            $table->foreign('maquina_id')->references('id')->on('maquinas');
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
        Schema::dropIfExists('maquina_reservadas');
    }
};
