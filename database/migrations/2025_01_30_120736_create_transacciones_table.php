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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semana_id')->nullable();
            $table->foreign('semana_id')->references('id')->on('semanas');
            $table->bigInteger('nro_pago')->unique()->nullable();
            $table->string('nombres');
            $table->bigInteger('dni')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('monto', 10, 2);
            $table->unsignedBigInteger('tipo_transaccion_id');
            $table->foreign('tipo_transaccion_id')->references('id')->on('tipo_transacciones');
            $table->boolean('estado')->nullable();
            $table->boolean('anulado');
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
        Schema::dropIfExists('transacciones');
    }
};
