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
        Schema::create('informe_semanals', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            //text->alamacena más de 255 caracteres
            $table->text('nota_semanal')->nullable();
            $table->boolean('estado')->default(true);
            $table->string('informe_url');
            $table->unsignedBigInteger('semana_id');
            $table->unsignedBigInteger('area_id');
            $table->foreign('semana_id')-> references('id')->on('semanas');
            $table->foreign('area_id')-> references('id')->on('areas');
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
        Schema::dropIfExists('informe_semanals');
    }
};
