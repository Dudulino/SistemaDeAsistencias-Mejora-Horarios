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
        Schema::create('cumplio__responsabilidad__semanals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('colaborador_area_id');
            $table->foreign('colaborador_area_id')->references('id')->on('colaboradores_por__areas');
            $table->unsignedBigInteger('responsabilidad_id');
            $table->foreign('responsabilidad_id')->references('id')->on('responsabilidades_semanales');
            $table->unsignedBigInteger('semana_id');
            $table->foreign('semana_id')->references('id')->on('semanas');
            $table->boolean('cumplio');
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
        Schema::dropIfExists('cumplio__responsabilidad__semanals');
    }
};
