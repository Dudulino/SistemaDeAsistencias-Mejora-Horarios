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
        Schema::create('integrantes_reuniones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reunion_programada_id');
            $table->foreign('reunion_programada_id')->references('id')->on('reuniones_programadas');
            $table->unsignedBigInteger('colaborador_id');
            $table->foreign('colaborador_id')->references('id')->on('colaboradores');
            $table->boolean('estado')->default(true);
            $table->boolean('asistio')->default(false);
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
        Schema::dropIfExists('integrantes_reuniones');
    }
};
