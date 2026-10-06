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
        Schema::create('horario_virtual_colaboradors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('horario_virtual_id');
            $table->foreign('horario_virtual_id')->references('id')->on('horarios__virtuales');
            $table->unsignedBigInteger('semana_id');
            $table->foreign('semana_id')->references('id')->on('semanas');
            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');
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
        Schema::dropIfExists('horario_virtual_colaboradors');
    }
};
