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
        Schema::table('semanas', function(Blueprint $table) {
            if(!Schema::hasColumn('semanas', 'caja_abierta')) {
                $table->boolean('caja_abierta')->default(0);
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
        Schema::table('semanas', function (Blueprint $table) {
            $table->dropColumn('caja_abierta');
        });
    }
};
