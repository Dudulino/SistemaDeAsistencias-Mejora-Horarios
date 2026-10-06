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
        Schema::create('registro__mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('computadora_id');
            $table->foreign('computadora_id')->references('id')->on('computadora_colaboradors');
            $table->date('fecha');
            $table->string('registro_incidencia');
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('registro__mantenimientos');
    }
};
