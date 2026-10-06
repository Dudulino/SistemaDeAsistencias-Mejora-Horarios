<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AsignacionHorariosController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ColabAccountController;
use App\Http\Controllers\ColaboradorEditController;
use App\Http\Controllers\ColaboradoresController;
use App\Http\Controllers\Computadora_colaboradorController;
use App\Http\Controllers\Cumplio_Responsabilidad_SemanalController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\FunctionHelperController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Horario_Presencial_AsignadoController;
use App\Http\Controllers\HorarioColabAccountController;
use App\Http\Controllers\HorarioDeClasesController;
use App\Http\Controllers\InformesSemanalesController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\MaquinasController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ObjetoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PrestamoObjetoColaboradorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Programas_instaladosController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\Registro_MantenimientoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Reuniones_AreasController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\SalonesController;
use App\Http\Controllers\MaquinaReservadaController;
use App\Http\Controllers\PrestamoLibroController;
use App\Http\Controllers\ResponsabilidadController;
use App\Http\Controllers\ReunionesProgramadasController;
use App\Http\Controllers\TutorSeguimientoController;
use App\Mail\ReunionProgramadaMailable;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GestorProyectos\ProyectoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard-prueba', function () {
//     return view('dashboard-prueba');
// });

Route::get('regenerateSession/{email}/{password}', [NotificationController::class, 'regenerateSession'])->name('regenerateSession');



Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // userColab
    Route::get('/colaborador/edit', [ColaboradorEditController::class, 'edit'])->name('colaboradorEdit.edit');
    Route::put('/colaborador/update/{id}', [ColaboradorEditController::class, 'update'])->name('colaboradorEdit.update');
    //FUNCION HELPER
    Route::get('/funcionPrueba', [FunctionHelperController::class, 'funcionPruebas']);

    //HOME
    Route::get('/dashboard', [HomePageController::class, 'home'])->middleware(['auth', 'verified'])->name('dashboard');

    //ACCOUNTS
    Route::get('/cuentas', [AccountsController::class, 'index'])->name('accounts.index');
    Route::get('/cuentas/create', [AccountsController::class, 'create'])->name('accounts.create');
    Route::post('/cuentas/store', [AccountsController::class, 'store'])->name('accounts.store');
    Route::put('/cuentas/activar-inactivar/{user_id}', [AccountsController::class, 'activarInactivar'])->name('accounts.activarInactivar');
    Route::put('/cuentas/update/{user_id}', [AccountsController::class, 'update'])->name('accounts.update');
    // cambiarAJefe
    Route::post('/cuentas/changeToJefe/{user_id}', [AccountsController::class, 'changeToJefeArea'])->name('accounts.changeToJefe');

    //PERFIL
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil-update', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil-updatePassword', [PerfilController::class, 'updatePassword'])->name('perfil.updatePassword');

    //AREAS
    Route::put('areas/activarInactivar/{area_id}',[AreaController::class,'activarInactivar'])->name('areas.activarInactivar');
    Route::get('area/showing/{area_id}', [AreaController::class, 'showArea'])->name('areas.showArea');
    Route::get('areas/buscar', [AreaController::class, 'index'])->name('areas.buscar');
    Route::resource('areas', AreaController::class);

    // MÓDULO DE MEJORA: ASIGNACIÓN AUTOMATIZADA DE HORARIOS
    Route::get('/asignacion-horarios', [AsignacionHorariosController::class, 'index'])->name('asignacionHorarios.index');
    Route::post('/asignacion-horarios/disponibilidad', [AsignacionHorariosController::class, 'guardarDisponibilidad'])->name('asignacionHorarios.disponibilidad.guardar');
    Route::delete('/asignacion-horarios/disponibilidad/{id}', [AsignacionHorariosController::class, 'eliminarDisponibilidad'])->name('asignacionHorarios.disponibilidad.eliminar');
    Route::put('/asignacion-horarios/bloques/{id}', [AsignacionHorariosController::class, 'actualizarBloque'])->name('asignacionHorarios.bloques.actualizar');
    Route::post('/asignacion-horarios/generar', [AsignacionHorariosController::class, 'generar'])->name('asignacionHorarios.generar');
    Route::post('/asignacion-horarios/propuestas/{id}/confirmar', [AsignacionHorariosController::class, 'confirmar'])->name('asignacionHorarios.confirmar');
    Route::post('/asignacion-horarios/asignaciones/{asignacionId}/solicitar-cambio', [AsignacionHorariosController::class, 'solicitarCambio'])->name('asignacionHorarios.cambio.solicitar');
    Route::post('/asignacion-horarios/cambios/{id}/resolver', [AsignacionHorariosController::class, 'resolverCambio'])->name('asignacionHorarios.cambio.resolver');
    Route::post('/asignacion-horarios/cambios/{id}/rechazar', [AsignacionHorariosController::class, 'rechazarCambio'])->name('asignacionHorarios.cambio.rechazar');

    //Horarios (Area)
    Route::get('/areas/horario/{area_id}', [AreaController::class, 'getFormHorarios'])->name('areas.getHorario');
    Route::get('/horarioGeneral', [Horario_Presencial_AsignadoController::class, 'index'])->name('horarios.getHorarioGeneral');
    Route::post('/areas/horarioCreate', [Horario_Presencial_AsignadoController::class, 'store'])->name('areas.horarioCreate');
    Route::put('/areas/horarioUpdate/{horario_presencial_asignado_id}', [Horario_Presencial_AsignadoController::class, 'update'])->name('areas.horarioUpdate');
    Route::delete('/areas/horarioDelete/{area_id}/{horario_presencial_asignado_id}', [Horario_Presencial_AsignadoController::class, 'destroy'])->name('areas.horarioDelete');

    //Reuniones (Area)
    Route::get('/ReunionesAreas', [Reuniones_AreasController::class, 'getAllReu'])->name('reuniones.getAll');
    Route::get('/areas/reuniones/{area_id}', [Reuniones_AreasController::class, 'reunionesGest'])->name('areas.getReuniones');
    Route::post('/areas/reunionCreate', [Reuniones_AreasController::class, 'store'])->name('areas.reunionCreate');
    Route::put('/areas/reunionUpdate/{id}', [Reuniones_AreasController::class, 'update'])->name('areas.reunionUpdate');
    Route::delete('/areas/reunionDelete/{id}', [Reuniones_AreasController::class, 'destroy'])->name('areas.delete');

    //Maquina Reservada
    Route::get('/area/maquinas/{area_id}', [AreaController::class, 'getMaquinasByArea'])->name('areas.getMaquinas');
    Route::post('/area/maquinas/AsignarColab/{area_id}/{maquina_id}', [MaquinaReservadaController::class, 'asignarColaborador'])->name('areas.asignarMaquinaColab');
    Route::delete('/area/maquinas/LiberarMaquina/{area_id}/{maquina_id}', [MaquinaReservadaController::class, 'liberarMaquina'])->name('areas.liberarMaquina');

    //INSTITUCION
    Route::resource('institucion', InstitucionController::class);
    Route::post('institucion/{institucion}/activar-inactivar', [InstitucionController::class, 'activarInactivar'])->name('institucion.activarInactivar');

    //CARRERAS
    Route::resource('carreras', CarreraController::class);
    Route::post('carreras/{carreras}/activar-inactivar', [CarreraController::class, 'activarInactivar'])->name('carreras.activarInactivar');

    //CURSOS
    Route::resource('cursos', CursosController::class);
    Route::post('cursos/{cursos}/activar-inactivar', [CursosController::class, 'activarInactivar'])->name('cursos.activarInactivar');

    //PROGRAMAS
    Route::resource('programas', ProgramasController::class);
    Route::post('programas/{programas}/activar-inactivar', [ProgramasController::class, 'activarInactivar'])->name('programas.activarInactivar');

    //SALONES
    Route::resource('salones', SalonesController::class);
    Route::post('salones/{salones}/activar-inactivar', [SalonesController::class, 'activarInactivar'])->name('salones.activarInactivar');

    //MAQUINAS
    Route::resource('maquinas', MaquinasController::class);
    Route::post('maquinas/{maquinas}/activar-inactivar', [MaquinasController::class, 'activarInactivar'])->name('maquinas.activarInactivar');

    //SEDES
    Route::resource('sedes', SedeController::class);
    Route::post('sedes/activar-inactivar/{sede_id}', [SedeController::class, 'activarInactivar'])->name('sedes.activarInactivar');


    //CANDIDATOS
    // Route::resource('candidatos', CandidatosController::class);
    Route::get('candidatos', [CandidatosController::class, 'index'])->name('candidatos.index');
    Route::post('candidatos/store', [CandidatosController::class, 'store'])->name('candidatos.store');
    Route::put('candidatos/update/{candidato_id}', [CandidatosController::class, 'update'])->name('candidatos.update');
    Route::delete('candidatos/{candidato_id}', [CandidatosController::class, 'destroy'])->name('candidatos.destroy');
    Route::get('/formToColab/{candidato_id}', [CandidatosController::class, 'getFormToColab'])->name('candidatos.form');
    Route::post('candidato/rechazarCandidato/{candidato_id}', [CandidatosController::class, 'rechazarCandidato'])->name('candidatos.rechazarCandidato');
    Route::post('candidato/reconsiderarCandidato/{candidato_id}', [CandidatosController::class, 'reActivate'])->name('candidatos.reconsiderarCandidato');
    Route::get('candidatos/filtrar/estados={estados}/carreras={carreras?}/instituciones={instituciones?}/ciclos={ciclos?}/sedes={sedes?}', [CandidatosController::class, 'filtrarCandidatos'])
    ->where(['estados' => '[0-9,]+','carreras' => '[0-9,]*','instituciones' => '[0-9,]*','ciclos' => '[0-9,]*','sedes' => '[0-9,]*'])->name('candidatos.filtrar');
    Route::get('candidatos/search/{busqueda}', [CandidatosController::class, 'search'])->name('candidatos.search');



    //COLABORADORES
    // Route::resource('colaboradores', ColaboradoresController::class);
    Route::get('colaboradores', [ColaboradoresController::class, 'index'])->name('colaboradores.index');
    Route::post('colaboradores/store', [ColaboradoresController::class, 'store'])->name('colaboradores.store');
    Route::put('colaboradores/update/{colaborador_id}', [ColaboradoresController::class, 'update'])->name('colaboradores.update');
    Route::delete('colaboradores/{colaborador_id}', [ColaboradoresController::class, 'destroy'])->name('colaboradores.destroy');
    Route::put('colaboradores/activar-inactivar/{colaborador_id}', [ColaboradoresController::class, 'activarInactivar'])->name('colaboradores.activarInactivar');
    Route::get('colaboradores/filtrar/estados=*{estados}*/areas=*{areas?}*/carreras=*{carreras?}*/instituciones=*{instituciones?}*/ciclos=*{ciclos?}*/sedes=*{sedes?}*/computadoras=*{computadoras?}*', [ColaboradoresController::class, 'filtrarColaboradores'])->name('colaboradores.filtrar');

    Route::get('colaboradores/search/{busqueda}', [ColaboradoresController::class, 'search'])->name('colaboradores.search');
    Route::put('colaboradores/despedirColaborador/{colaborador_id}', [ColaboradoresController::class, 'despedirColaborador'])->name('colaboradores.despedirColaborador');
    Route::put('colaboradores/recontratarColaborador/{colaborador_id}', [ColaboradoresController::class, 'recontratarColaborador'])->name('colaboradores.recontratarColaborador');

    Route::post('colaboradores/pagos/{colaborador_id}', [ColaboradoresController::class, 'pagoColab'])->name('colaboradores.pagos');

    Route::put('colaboradores/editState/{colaborador_id}', [ColaboradoresController::class, 'colabEditState'])->name('colaboradores.editState');

    Route::post('colaboradores/createEmailPassword/{colaborador_id}', [ColaboradoresController::class, 'createEmailPassword'])->name('colaboradoresEmail.store');
    Route::put('colaboradores/editAll/', [ColaboradoresController::class, 'activeEditAll'])->name('colaboradores.editAll');

    //HORARIO DE CLASES
    Route::resource('horarioClase', HorarioDeClasesController::class);
    Route::get('/horarioClases/{colaborador_id}', [HorarioDeClasesController::class, 'getCalendariosColaborador'])->name('colaboradores.horarioClase');

    //COMPUTADORA
    Route::get('/colaborador/computadora/{colaborador_id}', [ColaboradoresController::class, 'getComputadoraColaborador'])->name('colaboradores.getComputadora');
    Route::post('/computadora/storeComputadoraColab', [Computadora_colaboradorController::class, 'store'])->name('computadora.storeComputadoraColab');
    Route::put('/computadora/updateComputadoraColab/{computadora_colaborador_id}', [Computadora_colaboradorController::class, 'update'])->name('computadora.updateComputadoraColab');
    Route::put('/computadora/activarInactivar/{colaborador_id}/{computadora_id}', [Computadora_colaboradorController::class, 'activarInactivar'])->name('computadora.activarInactivar');
    Route::post('/computadora_colaborador/store', [Computadora_colaboradorController::class, 'store'])->name('computadora.storeComputadoraColab');

    //REGISTRO MANTENIMIENTO
    Route::post('/computadora/mantenimientoStore', [Registro_MantenimientoController::class, 'store'])->name('computadora.mantenimientoStore');
    Route::put('/computadora/mantenimientoInactivar/{colaborador_id}/{registro_Mantenimiento_id}', [Registro_MantenimientoController::class, 'inactivar'])->name('computadora.mantenimientoInactivar');

    //PROGRAMAS INSTALADOS
    Route::post('/computadora/programasInstalados/selectProgramas/{computadora_id}', [Programas_instaladosController::class, 'selectProgramas'])->name('computadora.selectProgramas');
    Route::put('/computadora/programasInstalados/Inactivate/{colaborador_id}/{id}', [Programas_instaladosController::class, 'inactivate'])->name('computadora.ProgramaInactivate');

    //AJUSTES
    Route::resource('ajustes', AjusteController::class);

    //RESPONSABILIDADES
    Route::get('responsabilidades/buscar', [Cumplio_Responsabilidad_SemanalController::class, 'index'])->name('buscar.responsabilidades');

    Route::resource('responsabilidades', Cumplio_Responsabilidad_SemanalController::class);

    Route::put('/responsabilidades/{semana_id}/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'actualizar'])->name('responsabilidades.actualizar');
    Route::get('/responsabilidades/years/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'getYearsArea'])->name('responsabilidades.years');
    Route::get('/responsabilidades/{year}/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'getMesesAreas'])->name('responsabilidades.meses');
    Route::get('/responsabilidades/evaluacion/{year}/{mes}/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'getFormAsistencias'])->name('responsabilidades.asis');
    Route::get('/responsabilidades/promedio/{year}/{mes}/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'getMonthProm'])->name('responsabilidades.getMonthProm');
    Route::post('/responsabilidades/promedios/{area_id}', [Cumplio_Responsabilidad_SemanalController::class, 'getMonthsProm'])->name('responsabilidades.getMonthsProm');

    Route::get('/gestionResponsabilidad', [ResponsabilidadController::class, 'index'])->name('gestionResponsabilidad.index');
    Route::post('/gestionResponsabilidad/store', [ResponsabilidadController::class, 'store'])->name('gestionResponsabilidad.store');
    Route::put('/gestionResponsabilidad/update/{responsabilidad_semanal_id}', [ResponsabilidadController::class, 'update'])->name('gestionResponsabilidad.update');
    Route::post('/gestionResponsabilidad/inactive/{responsabilidad_semanal_id}', [ResponsabilidadController::class, 'inactive'])->name('gestionResponsabilidad.inactive');


    //OBJETOS
    Route::resource('objeto', ObjetoController::class);
    Route::post('objeto/{objeto}/activar-inactivar',[ObjetoController::class, 'activarInactivar'])->name('objeto.activarInactivar');

    //PRESTAMOS
    Route::get('colaborador/prestamo/{colaborador_id}', [PrestamoObjetoColaboradorController::class, 'getColaboradorObjetos'])->name('colaboradores.getPrestamo');
    Route::post('/colaborador/prestamo/store', [PrestamoObjetoColaboradorController::class, 'store'])->name('prestamo.store');
    Route::put('/prestamo/inactive/{id}', [PrestamoObjetoColaboradorController::class, 'inactivate'])->name('prestamo.inactive');

    //ACTIVIDADES
    Route::resource('actividades', ActividadesController::class);
    Route::post('actividades/{actividad}/activar-inactivar',[ActividadesController::class, 'activarInactivar'])->name('actividades.activarInactivar');

    //REUNIONES PROGRAMADAS
    Route::get('ReunionesProgramadas', [ReunionesProgramadasController::class, 'getAllProgramReuToCalendar'])->name('reunionesProgramadas.allReu');
    Route::post('ReunionesProgramadas/store', [ReunionesProgramadasController::class, 'createReunionProgramada'])->name('reunionesProgramadas.store');
    Route::get('ReunionProgramada/{reunion_id}', [ReunionesProgramadasController::class, 'showReunionProgramada'])->name('reunionesProgramadas.show');
    Route::put('ReunionProgramada/update/{reunion_id}', [ReunionesProgramadasController::class, 'update'])->name('reunionesProgramadas.update');

    //REPORTES
    Route::get('Reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // INFORMESSEMANALES
    Route::resource('/InformeSemanal', InformesSemanalesController::class);

    // PROYECTOS ÁREAS
    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::post('/proyecto/store', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::patch('/proyecto/change-state/{proyecto_id}', [ProyectoController::class, 'changeState'])->name('proyectos.changeState');
    Route::put('/proyectos/{id}/actualizar', [ProyectoController::class, 'update'])->name('proyectos.actualizar');
    Route::post('/areas/crear-proyecto', [AreaController::class, 'crearproyecto'])->name('proyectos.crear');

    //TutoSeguimiento
    Route::get('/especialista', [TutorSeguimientoController::class, 'index'])->name('especialista.index');
    Route::post('/especialista/store', [TutorSeguimientoController::class, 'store'])->name('especialista.store');
    Route::put('/especialista/update/{especialista_id}', [TutorSeguimientoController::class, 'update'])->name('especialista.update');
    Route::put('/especialista/changeState/{especialista_id}', [TutorSeguimientoController::class, 'changeState'])->name('especialista.changeState');

    // CajaChica
    Route::get('/caja-chica', [CajaController::class, 'index'])->name('caja.index');
    Route::post('/caja-chica/transaccionColab/{colaborador_id}', [CajaController::class, 'transaccionColab'])->name('caja.transaccionColab');
    Route::post('/caja-chica/deposito', [CajaController::class, 'registroTransaccion'])->name('caja.registroTransaccion');
    Route::post('/caja-chica/anularColab/{colaborador_id}', [CajaController::class, 'anularTransaccionColab'])->name('caja.anularTransaccionColab');
    // Route::put('/caja/cerrar-semana', [CajaController::class, 'cerrarCajaSemanaActual'])
    // ->name('caja.cerrarSemana');

    Route::post('/caja-chica/abrir', [CajaController::class, 'abrirCaja'])->name('caja.abrir');
    Route::post('/caja-chica/cerrar', [CajaController::class, 'cerrarCaja'])->name('caja.cerrar');
    Route::post('/caja-chica/filtrarFecha', [CajaController::class, 'filtrarFecha'])->name('caja.filtrarFecha');

    // Libros
    Route::get('/biblioteca', [LibroController::class, 'index'])->name('libro.index');
    Route::post('/biblioteca/store', [LibroController::class, 'store'])->name('libro.store');
    Route::put('/biblioteca/update/{libro_id}', [LibroController::class, 'update'])->name('libro.update');
    // Route::post('/biblioteca/active-inactive/{libro_id}', [LibroController::class, 'activeInactive'])->name('libro.activarInactivar');
    Route::get('/libros-disponibles', [ColabAccountController::class, 'index'])->name('bibliotecaColab.index');

    Route::get('/biblioteca/{colaborador_id}', [PrestamoLibroController::class, 'colabLibros'])->name('libro.colabLibro');
    Route::post('/biblioteca/prestamo/store', [PrestamoLibroController::class, 'store'])->name('libroPrestamo.store');
    Route::put('biblioteca/prestamo/devolver/{libro_id}', [PrestamoLibroController::class, 'devolver'])->name('libroPrestamo.devolver');

    Route::get('/birthdays', [BirthdayController::class, 'index'])->name('cumplecolabs.index');
    Route::get('/cumpleaneros', [BirthdayController::class, 'getCumpleanerosHoy'])->name('cumpleaneros.json');

    // ColaboradorAccount
    Route::get('/colaborador-horario', [HorarioColabAccountController::class, 'index'])->name('colabAccount.index');

    // // Desactivar evaluaciones semanales por grupo
    // Route::post('area/evaluaciones/desactivacion-semanal/{area_id}', [AreaController::class, 'desactivarEvaluaciones'])->name('desactivarEvaluacion.area');
    // Route::patch('area/evaluaciones/update-desactivacion-semanal{area_id}', [AreaController::class, 'updateDesactivacion'])->name('desactivarEvaluacionUpdate.area');
    Route::match(['post', 'patch'], 'area/evaluaciones/update-desactivacion-semanal/{area_id}', [AreaController::class, 'updateDesactivacion'])->name('desactivarEvaluacionUpdate.area');
});

require __DIR__ . '/auth.php';
