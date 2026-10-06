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
        Schema::table('colaboradores', function (Blueprint $table) {
            if (!Schema::hasColumn('colaboradores', 'especialista_id')) {
                $table->unsignedBigInteger('especialista_id')->nullable()->after('candidato_id');
                $table->foreign('especialista_id')->references('id')->on('especialistas');
            }
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropColumn('especialista_id');
        });
    }
};
