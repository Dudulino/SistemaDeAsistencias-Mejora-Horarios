<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformesSemanalesRequest;
use App\Models\Area;
use App\Models\Colaboradores_por_Area;
use App\Models\InformeSemanal;
use App\Models\Semanas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Http\Log;
class InformesSemanalesController extends Controller
{

    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAreaAccess($request->area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            // obtiene el año, mes, area_id, semana_id
            $year = $request->year;
            $mes = $request->mes;
            $area_id = $request->area_id;
            $semana_id = $request->semana_id;
            $nroSemana = $request->index;

            $returnRoute = route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id]);

            // validacion de la semana
            $semana = Semanas::find($semana_id);
            $thisWeekMonday = Carbon::today()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

            if ($thisSemana->id < $semana->id) {
                $warnings['semana_futura' . $semana_id] = 'No se puede crear informes en semanas futuras.';
                return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                    ->with('InformeWarning', $warnings)
                    ->with('current_semana_id', $request->index);
            }

            // validacion de errores
            $errors = [];

            // validacion de título
            if (!isset($request->titulo) || trim($request->titulo) === '') {
                $errors['titulo' . $semana_id] = 'El título es un campo requerido.';
            } else if (strlen($request->titulo) > 150) {
                $errors['titulo' . $semana_id] = 'El título no puede exceder los 150 caracteres.';
            }

            // validacion de nota_semanal
            if (strlen($request->nota_semanal) > 2000) {
                $errors['nota_semanal' . $semana_id] = 'La nota semanal no puede exceder los 2000 caracteres.';
            }

            // validacion de informe_url
            if (!$request->hasFile('informe_url')) {
                $errors['informe_url' . $semana_id] = 'Es obligatorio subir un archivo.';
            } else {
                $informe = $request->file('informe_url');
                $extensionVal = $informe->getClientOriginalExtension();
                $extensiones = ['pdf'];

                if (!in_array($extensionVal, $extensiones)) {
                    $errors['informe_url' . $semana_id] = 'El informe debe ser un archivo de tipo: ' . implode(', ', $extensiones);
                }
            }

            // si hay errores, redirigir
            if (!empty($errors)) {
                return redirect($returnRoute)
                    ->with('InformeError', $errors)
                    ->with('current_semana_id', $request->index);
            }

            // Procesar la creación del informe
            $nombreInforme = time() . '.' . $informe->getClientOriginalExtension();
            $informe->move(public_path('storage/informes'), $nombreInforme);

        // crear el informe
        InformeSemanal::create([
            'titulo' => $request->titulo,
            'nota_semanal' => $request->nota_semanal,
            'informe_url' => $nombreInforme,
            'semana_id' => $semana_id,
            'area_id' => $area_id,
            'dia' => Carbon::now()->toDateString(),
            'hora' => Carbon::now()->toTimeString(),
        ]);

        DB::commit();
        return redirect($returnRoute)->with('success', 'Informe guardado correctamente.');
    } catch (Exception $e) {
        // return $e;
        DB::rollBack();
        return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
            ->with('error', 'Ocurrió un error.');
    }
    }


    public function update(Request $request, $InformeSemanal)
    {
        $access = FunctionHelperController::verifyAreaAccess($request->area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {
            $year = $request->year;
            $mes = $request->mes;
            $area_id = $request->area_id;
            $semana_id = $request->semana_id;
            $nroSemana = $request->index;

            $returnRoute = route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id]);

            $informe = InformeSemanal::findOrFail($InformeSemanal);

            // Validaciones
            $errors = [];

            if (!isset($request->titulo)) {
                $errors['titulo' .$semana_id] = 'El titulo es un campo requerido.';
            } else {
                if (strlen($request->titulo) > 150) {
                    $errors['titulo' .$semana_id] = 'El titulo no puede exceder los 150 caracteres.';
                }
            }


            if (strlen($request->nota_semanal) > 2000) {
                $errors['nota_semanal' .$semana_id] = 'La nota semanal no puede exceder los 2000 caracteres.';
            }


            if ($request->hasFile('informe_url')) {
                $extensiones = ['pdf'];
                $extensionVal = $request->file('informe_url')->getClientOriginalExtension();

                if (!in_array($extensionVal, $extensiones)) {
                    $errors['informe_url' .$semana_id] = 'El informe debe ser un archivo de tipo: ' . implode(', ', $extensiones);
                }
            }

            if (!empty($errors)) {
                return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])->with('InformeError',$errors)->with('current_semana_id', $request->index);
            }

            // Preparar los datos para actualizar
            $datosActualizar = $request->except(['informe_url']);

            $datosActualizar['dia'] = Carbon::now()->toDateString();
            $datosActualizar['hora'] = Carbon::now()->toTimeString();

            if ($request->hasFile('informe_url')) {
                $rutaPublica = public_path('storage/informes');

                if ($informe->informe_url && file_exists($rutaPublica . '/' . $informe->informe_url)) {
                    unlink($rutaPublica . '/' . $informe->informe_url);
                }

                $archivo = $request->file('informe_url');
                $nombreInforme = time() . '.' . $archivo->getClientOriginalExtension();
                $archivo->move($rutaPublica, $nombreInforme);

                $datosActualizar['informe_url'] = $nombreInforme;
            }

            $informe->update($datosActualizar);
            DB::commit();
            return redirect($returnRoute)->with('success', 'Informe actualizado correctamente.');
        } catch (Exception $e) {
            return redirect()->route($returnRoute)->with('error', 'Ocurrió un error.');
        }
    }


    public function show(Request $request, $InformeSemanal)
    {
        $access = FunctionHelperController::verifyAreaAccess($request->area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        // Obtener el informe con el ID proporcionado
        $informe = InformeSemanal::findOrFail($InformeSemanal);

        $year = $informe->year;
        $mes = $informe->mes;
        $semana_id = $informe->semana_id;
        $area_id = $informe->area_id;

        // Pasar todos los datos a la vista
        return view('inspiniaViews.responsabilidades.asistencia', compact('informe', 'year', 'mes', 'semana_id', 'area_id'));
    }

    public function destroy(Request $request, $InformeSemanal)
    {
        $access = FunctionHelperController::verifyAreaAccess($request->area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        $area_id = $request->area_id;
        $year = $request->year;
        $mes = $request->mes;
        try{

            $informe = InformeSemanal::findOrFail($InformeSemanal);

            // Eliminar el archivo asociado si existe
            if ($informe->informe_url) {
                $rutaPublica = public_path('storage/informes');
                if (file_exists($rutaPublica . '/' . $informe->informe_url)) {
                    unlink($rutaPublica . '/' . $informe->informe_url);
                }
            }

            $informe->delete();

            DB::commit();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])->with('success', 'Informe eliminado correctamente.');
        } catch(Exception $e){
            DB::rollBack();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])->with('error', 'Ocurrió un error.');
        }
    }

}
