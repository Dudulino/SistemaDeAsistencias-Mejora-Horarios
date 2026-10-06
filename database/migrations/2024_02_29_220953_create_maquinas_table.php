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
        Schema::create('maquinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->boolean('estado')->default(True);
            $table->string('detalles_tecnicos', 400);
            $table->integer('num_identificador'); //used to be unique
            $table->unSignedBigInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salones');
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
        Schema::dropIfExists('maquinas');
    }
};
