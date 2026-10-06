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
        Schema::create('registro_actividads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('colaborador_area_id');
            $table->foreign('colaborador_area_id')->references('id')->on('colaboradores_por__areas');
            $table->boolean('estado');
            $table->date('fecha');
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
        Schema::dropIfExists('registro_actividads');
    }
};
