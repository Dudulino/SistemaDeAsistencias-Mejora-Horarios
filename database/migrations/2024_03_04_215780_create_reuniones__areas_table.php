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
        Schema::create('reuniones__areas', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->string('dia');
            $table->string('hora_inicial');
            $table->string('hora_final');
            $table->string('disponibilidad');
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
        Schema::dropIfExists('reuniones__areas');
    }
};
