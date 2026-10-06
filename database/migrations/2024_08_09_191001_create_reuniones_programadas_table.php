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
        Schema::create('reuniones_programadas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('hora_inicial');
            $table->string('hora_final');
            $table->string('disponibilidad');
            $table->string('url')->nullable();
            $table->string('direccion')->nullable();
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('reuniones_programadas');
    }
};
