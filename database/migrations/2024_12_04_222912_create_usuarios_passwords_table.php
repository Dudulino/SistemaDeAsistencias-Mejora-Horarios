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
        // Crear la tabla usuarios_passwords
        Schema::create('usuarios_passwords', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('password');
            $table->timestamps();
        });

        // Modificar la tabla colaboradores_por__areas
        Schema::table('colaboradores_por__areas', function (Blueprint $table) {
            $table->boolean('jefe_area')->default(false)->after('estado');
        });

        // Modificar la tabla colaboradores
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->boolean('editable')->default(true)->after('candidato_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir los cambios en la tabla colaboradores
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropColumn('editable');
        });

        // Revertir los cambios en la tabla colaboradores_por__areas
        Schema::table('colaboradores_por__areas', function (Blueprint $table) {
            $table->dropColumn('jefe_area');
        });

        // Eliminar la tabla usuarios_passwords
        Schema::dropIfExists('usuarios_passwords');
    }
};
