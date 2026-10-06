<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AsignacionPracticante;
use App\Models\Colaboradores;
use App\Models\ConfiguracionBloqueHorario;
use App\Models\DisponibilidadPracticante;
use App\Models\PropuestaHorario;
use App\Models\SolicitudCambioHorario;
use App\Services\HorarioAsignacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RuntimeException;

class AsignacionHorariosController extends Controller
{
    public function index(Request $request, HorarioAsignacionService $service)
    {
        $userData = FunctionHelperController::getUserRol();
        $canManage = $userData['isAdmin'] || $userData['isBoss'];
        $colaboradorActual = $userData['colaboradores'];

        if (!$canManage && !$colaboradorActual) {
            return redirect()->route('dashboard')->with('error', 'Su cuenta no está vinculada a un practicante o a un rol de gestión.');
        }

        $bloques = $service->sincronizarBloques();
        $areas = Area::where('estado', 1)->orderBy('especializacion')->get();

        $colaboradores = $canManage
            ? Colaboradores::with('candidato')->where('estado', 1)->get()->sortBy(fn ($c) => strtolower(($c->candidato->nombre ?? '') . ' ' . ($c->candidato->apellido ?? '')))->values()
            : collect([$colaboradorActual->load('candidato')]);

        $colaboradorFiltro = $canManage
            ? ($request->filled('colaborador_id') ? (int) $request->colaborador_id : null)
            : $colaboradorActual->id;

        $disponibilidades = DisponibilidadPracticante::with('colaborador.candidato')
            ->when($colaboradorFiltro, fn ($q) => $q->where('colaborador_id', $colaboradorFiltro))
            ->where('estado', 1)
            ->orderByRaw("FIELD(dia, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')")
            ->orderBy('hora_inicial')
            ->get();

        $asignacionesQuery = AsignacionPracticante::with([
            'colaborador.candidato',
            'bloque.bloqueBase.area',
            'bloque.bloqueBase.horario_presencial',
        ])->where('estado', 'activa');

        if (!$canManage) {
            $asignacionesQuery->where('colaborador_id', $colaboradorActual->id);
        } else {
            if ($request->filled('colaborador_id')) {
                $asignacionesQuery->where('colaborador_id', $request->colaborador_id);
            }
            if ($request->filled('area_id')) {
                $areaId = (int) $request->area_id;
                $asignacionesQuery->whereHas('bloque.bloqueBase', fn ($q) => $q->where('area_id', $areaId));
            }
            if ($request->filled('turno')) {
                $turno = $request->turno;
                $asignacionesQuery->whereHas('bloque', fn ($q) => $q->where('turno', $turno));
            }
        }

        $asignaciones = $asignacionesQuery->get()->sortBy(function ($a) {
            $h = $a->bloque?->bloqueBase?->horario_presencial;
            return ($h?->dia ?? '') . ($h?->hora_inicial ?? '');
        })->values();

        $solicitudes = SolicitudCambioHorario::with([
            'colaborador.candidato',
            'asignacion.bloque.bloqueBase.area',
            'asignacion.bloque.bloqueBase.horario_presencial',
            'nuevaAsignacion.bloque.bloqueBase.area',
            'nuevaAsignacion.bloque.bloqueBase.horario_presencial',
        ])
            ->when(!$canManage, fn ($q) => $q->where('colaborador_id', $colaboradorActual->id))
            ->latest()
            ->limit(50)
            ->get();

        $ultimaPropuesta = null;
        if ($canManage) {
            $ultimaPropuesta = PropuestaHorario::with([
                'detalles.colaborador.candidato',
                'detalles.bloque.bloqueBase.area',
                'detalles.bloque.bloqueBase.horario_presencial',
            ])->latest()->first();
        }

        $estadisticas = [
            'practicantes_con_disponibilidad' => DisponibilidadPracticante::where('estado', 1)->distinct('colaborador_id')->count('colaborador_id'),
            'bloques_activos' => $bloques->where('estado', true)->count(),
            'asignaciones_activas' => AsignacionPracticante::where('estado', 'activa')->count(),
            'cambios_pendientes' => SolicitudCambioHorario::where('estado', 'pendiente')->count(),
        ];

        return view('inspiniaViews.horarios.asignacion_automatica', compact(
            'userData', 'canManage', 'colaboradorActual', 'colaboradores', 'bloques', 'areas',
            'disponibilidades', 'asignaciones', 'solicitudes', 'ultimaPropuesta', 'estadisticas'
        ));
    }

    public function guardarDisponibilidad(Request $request)
    {
        $userData = FunctionHelperController::getUserRol();
        $canManage = $userData['isAdmin'] || $userData['isBoss'];
        $colaboradorActual = $userData['colaboradores'];

        $data = $request->validate([
            'disponibilidad_id' => 'nullable|integer|exists:disponibilidad_practicantes,id',
            'colaborador_id' => 'nullable|integer|exists:colaboradores,id',
            'dia' => ['required', Rule::in(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'])],
            'hora_inicial' => 'required|date_format:H:i',
            'hora_final' => 'required|date_format:H:i|after:hora_inicial',
            'tipo' => ['required', Rule::in(['disponible','no_disponible'])],
            'origen' => ['required', Rule::in(['estudio','trabajo','practicas','otro'])],
            'modalidad' => ['required', Rule::in(['Presencial','Virtual','Mixta'])],
            'observacion' => 'nullable|string|max:255',
        ]);

        $colaboradorId = $canManage ? (int) ($data['colaborador_id'] ?? 0) : (int) ($colaboradorActual->id ?? 0);
        if (!$colaboradorId) {
            return back()->with('error', 'Debe seleccionar un practicante válido.')->withInput();
        }

        $payload = [
            'colaborador_id' => $colaboradorId,
            'dia' => $data['dia'],
            'hora_inicial' => $data['hora_inicial'],
            'hora_final' => $data['hora_final'],
            'tipo' => $data['tipo'],
            'origen' => $data['origen'],
            'modalidad' => $data['modalidad'],
            'observacion' => $data['observacion'] ?? null,
            'estado' => true,
        ];

        if (!empty($data['disponibilidad_id'])) {
            $registro = DisponibilidadPracticante::findOrFail($data['disponibilidad_id']);
            if (!$canManage && $registro->colaborador_id !== $colaboradorId) {
                abort(403);
            }
            $registro->update($payload);
            $mensaje = 'Disponibilidad actualizada correctamente.';
        } else {
            DisponibilidadPracticante::create($payload);
            $mensaje = 'Disponibilidad registrada correctamente.';
        }

        return back()->with('success', $mensaje);
    }

    public function eliminarDisponibilidad(int $id)
    {
        $userData = FunctionHelperController::getUserRol();
        $canManage = $userData['isAdmin'] || $userData['isBoss'];
        $registro = DisponibilidadPracticante::findOrFail($id);

        if (!$canManage && (!$userData['colaboradores'] || $registro->colaborador_id !== $userData['colaboradores']->id)) {
            abort(403);
        }

        $registro->update(['estado' => false]);
        return back()->with('success', 'Franja eliminada de la disponibilidad activa.');
    }

    public function actualizarBloque(Request $request, int $id)
    {
        if (!FunctionHelperController::verifyAdminAccess()) {
            return back()->with('error', 'Solo el administrador puede modificar cupos y turnos.');
        }

        $data = $request->validate([
            'turno' => ['required', Rule::in(['Mañana','Tarde','Personalizado'])],
            'cupo_min' => 'required|integer|min:1|max:20',
            'cupo_max' => 'required|integer|min:1|max:20|gte:cupo_min',
            'modalidad' => ['required', Rule::in(['Presencial','Virtual','Mixta'])],
            'estado' => 'nullable|boolean',
        ]);

        $bloque = ConfiguracionBloqueHorario::findOrFail($id);
        $bloque->update([
            'turno' => $data['turno'],
            'cupo_min' => $data['cupo_min'],
            'cupo_max' => $data['cupo_max'],
            'modalidad' => $data['modalidad'],
            'estado' => $request->boolean('estado'),
        ]);

        return back()->with('success', 'Bloque actualizado. El motor usará estos parámetros en la siguiente propuesta.');
    }

    public function generar(HorarioAsignacionService $service)
    {
        if (!FunctionHelperController::verifyAdminAccess()) {
            return back()->with('error', 'Solo el administrador puede generar una propuesta automática.');
        }

        try {
            $propuesta = $service->generarPropuesta(auth()->id());
            $mensaje = $propuesta->factible
                ? 'Propuesta completa generada. Revísela y confírmela para guardar los horarios.'
                : 'Se generó una propuesta parcial. Revise los bloques pendientes antes de confirmar.';
            return redirect()->route('asignacionHorarios.index')->with($propuesta->factible ? 'success' : 'warning', $mensaje);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirmar(int $id, HorarioAsignacionService $service)
    {
        if (!FunctionHelperController::verifyAdminAccess()) {
            return back()->with('error', 'Solo el administrador puede confirmar una propuesta.');
        }

        try {
            $service->confirmarPropuesta($id);
            return redirect()->route('asignacionHorarios.index')->with('success', 'Propuesta confirmada y horarios persistidos correctamente.');
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function solicitarCambio(Request $request, int $asignacionId)
    {
        $data = $request->validate([
            'tipo_cambio' => ['required', Rule::in(['turno','area','general'])],
            'motivo' => 'required|string|min:5|max:500',
        ]);

        $asignacion = AsignacionPracticante::where('estado', 'activa')->findOrFail($asignacionId);
        $userData = FunctionHelperController::getUserRol();
        $isAdmin = $userData['isAdmin'];

        if (!$isAdmin && (!$userData['colaboradores'] || $asignacion->colaborador_id !== $userData['colaboradores']->id)) {
            abort(403);
        }

        $existe = SolicitudCambioHorario::where('asignacion_id', $asignacion->id)
            ->where('estado', 'pendiente')->exists();
        if ($existe) {
            return back()->with('warning', 'Ya existe una solicitud pendiente para esta asignación.');
        }

        SolicitudCambioHorario::create([
            'asignacion_id' => $asignacion->id,
            'colaborador_id' => $asignacion->colaborador_id,
            'tipo_cambio' => $data['tipo_cambio'],
            'motivo' => $data['motivo'],
            'estado' => 'pendiente',
        ]);

        return back()->with('success', 'Solicitud registrada. El administrador podrá buscar una alternativa compatible.');
    }

    public function resolverCambio(int $id, HorarioAsignacionService $service)
    {
        if (!FunctionHelperController::verifyAdminAccess()) {
            return back()->with('error', 'Solo el administrador puede resolver solicitudes de cambio.');
        }

        try {
            $resultado = $service->resolverSolicitud($id, auth()->id());
            return back()->with(
                $resultado->estado === 'aprobada' ? 'success' : 'warning',
                $resultado->respuesta ?? 'Solicitud procesada.'
            );
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rechazarCambio(Request $request, int $id)
    {
        if (!FunctionHelperController::verifyAdminAccess()) {
            return back()->with('error', 'Solo el administrador puede rechazar solicitudes.');
        }

        $data = $request->validate(['respuesta' => 'required|string|min:3|max:500']);
        $solicitud = SolicitudCambioHorario::where('estado', 'pendiente')->findOrFail($id);
        $solicitud->update([
            'estado' => 'rechazada',
            'gestionado_por' => auth()->id(),
            'respuesta' => $data['respuesta'],
        ]);

        return back()->with('success', 'Solicitud rechazada y registrada en la trazabilidad.');
    }
}
