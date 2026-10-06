<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disponibilidad_practicantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('colaborador_id');
            $table->string('dia', 20);
            $table->time('hora_inicial');
            $table->time('hora_final');
            $table->string('tipo', 20)->default('disponible'); // disponible | no_disponible
            $table->string('origen', 30)->default('practicas'); // estudio | trabajo | practicas | otro
            $table->string('modalidad', 20)->default('Presencial');
            $table->string('observacion', 255)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('colaborador_id')->references('id')->on('colaboradores')->onDelete('cascade');
            $table->index(['colaborador_id', 'dia', 'estado'], 'disp_practicante_dia_idx');
        });

        Schema::create('configuracion_bloques_horario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('horario_presencial_asignado_id')->unique('cfg_bloque_hpa_unique');
            $table->string('turno', 30)->default('Mañana');
            $table->unsignedTinyInteger('cupo_min')->default(2);
            $table->unsignedTinyInteger('cupo_max')->default(5);
            $table->string('modalidad', 20)->default('Presencial');
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('horario_presencial_asignado_id', 'cfg_bloque_hpa_fk')
                ->references('id')->on('horario__presencial__asignados')->onDelete('cascade');
        });

        Schema::create('propuestas_horario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('generado_por')->nullable();
            $table->string('estado', 25)->default('borrador'); // borrador | confirmada | reemplazada | descartada
            $table->boolean('factible')->default(false);
            $table->decimal('puntaje_total', 10, 2)->default(0);
            $table->json('pendientes')->nullable();
            $table->json('resumen')->nullable();
            $table->timestamps();

            $table->foreign('generado_por')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('propuesta_horario_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('propuesta_id');
            $table->unsignedBigInteger('colaborador_id');
            $table->unsignedBigInteger('configuracion_bloque_id');
            $table->decimal('puntaje', 7, 2)->default(0);
            $table->json('explicacion')->nullable();
            $table->timestamps();

            $table->foreign('propuesta_id')->references('id')->on('propuestas_horario')->onDelete('cascade');
            $table->foreign('colaborador_id')->references('id')->on('colaboradores')->onDelete('cascade');
            $table->foreign('configuracion_bloque_id', 'prop_det_cfg_bloque_fk')
                ->references('id')->on('configuracion_bloques_horario')->onDelete('cascade');
            $table->unique(['propuesta_id', 'colaborador_id', 'configuracion_bloque_id'], 'prop_det_unique');
        });

        Schema::create('asignaciones_practicantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('colaborador_id');
            $table->unsignedBigInteger('configuracion_bloque_id');
            $table->unsignedBigInteger('propuesta_id')->nullable();
            $table->decimal('puntaje', 7, 2)->default(0);
            $table->json('explicacion')->nullable();
            $table->string('estado', 25)->default('activa'); // activa | reemplazada | cancelada
            $table->date('vigencia_desde')->nullable();
            $table->date('vigencia_hasta')->nullable();
            $table->timestamps();

            $table->foreign('colaborador_id')->references('id')->on('colaboradores')->onDelete('cascade');
            $table->foreign('configuracion_bloque_id', 'asig_cfg_bloque_fk')
                ->references('id')->on('configuracion_bloques_horario')->onDelete('cascade');
            $table->foreign('propuesta_id')->references('id')->on('propuestas_horario')->nullOnDelete();
            $table->index(['estado', 'configuracion_bloque_id'], 'asig_estado_bloque_idx');
            $table->index(['estado', 'colaborador_id'], 'asig_estado_colab_idx');
        });

        Schema::create('solicitudes_cambio_horario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignacion_id');
            $table->unsignedBigInteger('colaborador_id');
            $table->string('tipo_cambio', 25)->default('turno'); // turno | area | general
            $table->string('motivo', 500);
            $table->string('estado', 25)->default('pendiente'); // pendiente | aprobada | sin_alternativa | rechazada
            $table->unsignedBigInteger('nueva_asignacion_id')->nullable();
            $table->unsignedBigInteger('gestionado_por')->nullable();
            $table->string('respuesta', 500)->nullable();
            $table->timestamps();

            $table->foreign('asignacion_id')->references('id')->on('asignaciones_practicantes')->onDelete('cascade');
            $table->foreign('colaborador_id')->references('id')->on('colaboradores')->onDelete('cascade');
            $table->foreign('nueva_asignacion_id')->references('id')->on('asignaciones_practicantes')->nullOnDelete();
            $table->foreign('gestionado_por')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes_cambio_horario');
        Schema::dropIfExists('asignaciones_practicantes');
        Schema::dropIfExists('propuesta_horario_detalles');
        Schema::dropIfExists('propuestas_horario');
        Schema::dropIfExists('configuracion_bloques_horario');
        Schema::dropIfExists('disponibilidad_practicantes');
    }
};
