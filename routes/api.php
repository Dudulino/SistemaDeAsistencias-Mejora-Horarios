<?php

use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitucionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
------------------------------
        PRIMER ESCALÓN
------------------------------
*/
//INSTITUCIONES 
// Route::get('istitucion/show/{institucion_id}', [InstitucionController::class, 'show']);

// Route::post('apiLogin', [NotificationController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        // Route::get('instituciones/getall', [InstitucionController::class, 'getAll']);
        // Route::post('instituciones/storeJson', [InstitucionController::class, 'storeJSON']);
        // Route::put('instituciones/updateJSON/{institucion_id}', [InstitucionController::class, 'updateJSON']);
        // Route::put('instituciones/ActivarInactivarJSON/{institucion_id}', [InstitucionController::class, 'activarInactivarJSON']);
        // // return $request->user();
        // Route::get('notificaciones', [NotificationController::class, 'index']);
        
});

// Route::post('login', [NotificationController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function (){
        Route::get('notificaciones', [NotificationController::class, 'index'])->name('notificaciones');
        
        

});