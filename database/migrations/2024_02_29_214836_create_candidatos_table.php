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
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('dni', 8)->nullable()->unique(); //used to be unique
            $table->string('direccion', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('ciclo_de_estudiante')->nullable();
            $table->boolean('estado')->default(True);
            $table->unSignedBigInteger('sede_id')->nullable();
            $table->foreign('sede_id')->references('id')->on('sedes')->nullable();
            $table->unSignedBigInteger('carrera_id')->nullable();
            $table->foreign('carrera_id')->references('id')->on('carreras')->nullable();
            $table->string('correo')->nullable()->unique(); //used to be unique
            $table->string('celular')->nullable()->unique(); //used to be unique
            $table->string('icono')->nullable();
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
        Schema::dropIfExists('candidatos');
    }
};
