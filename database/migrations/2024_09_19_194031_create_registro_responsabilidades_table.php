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
        Schema::create('registro_responsabilidads', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado');
            $table->date('fecha');
            $table->unSignedBigInteger('responsabilidad_id');
            $table->foreign('responsabilidad_id')->references('id')->on('responsabilidades_semanales');
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
        Schema::dropIfExists('registro_responsabilidads');
    }
};
