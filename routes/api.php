<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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


//AUTH ENDPOINTS
Route::post('/login', 'Api\ApiAuthController@login')->middleware('throttle:25,60');
Route::post('/registro', 'Api\ApiAuthController@registro')->middleware('throttle:5,60');
Route::post('/googleauth', 'Auth\GoogleLoginController@handleAPICallback')->middleware('throttle:25,60');
Route::post('/registrar_google', 'Auth\GoogleLoginController@registrar_api')->name('google.register_google')->middleware('throttle:25,60');

Route::post('/forgottenPassword', 'Api\ApiAuthController@olvideClave' )->middleware('throttle:5,60');
Route::post('/updatePassword', 'Api\ApiAuthController@cambiarClave')->middleware('throttle:5,60');;


Route::get('/getFacultades', 'Api\FacultadController@getFacultades');
Route::get('/getCarreras', 'Api\CarreraController@getCarreras');
Route::get('/getCarrerasPorFacultad/{idFacultad}', 'Api\CarreraController@getCarrerasPorFacultad');


////APP ENDPOINTS
Route::middleware(['auth:api', ])->group(function () {
    
    // ESTUDIANTES
    Route::get('/getCarrerasYFacultades', 'Api\CarreraController@getCarrerasYFacultades');
    Route::get('/getPermisoAplicar', 'Api\ProyectoController@getPermisoAplicar');
    Route::get('/getProyectosDisponibles', 'Api\ProyectoController@getProyectosDisponibles');
    Route::get('/getMisProyectos', 'Api\ProyectoController@getMisProyectos');

    Route::get('/getAviableProjects', 'Api\ProyectoController@getAviableProjects');
    
    Route::post('/postAplicarProyecto', 'ProyectoxEstudianteController@aplicar');
    Route::post('/postDesaplicarProyecto', 'ProyectoxEstudianteController@deleteRow');
    Route::put('/updateEstudiantePerfil', 'PerfilController@updateMyProfile');
    Route::put('/updateEstudianteCarrera', 'PerfilController@updateMyCareer');

    // ADMIN ROUTES
    Route::middleware(['Administrador'])->group(function () {
        Route::prefix('admin')->group(function () {
            // PROYECTOS
            Route::get('/getAllProjects', 'Api\ProyectoController@getAllProjects');
            Route::get('/getHistorialDeProyectos', 'Api\ProyectoController@getHistorialDeProyectos');
            Route::put('/updateEstadoProyecto', 'Api\ProyectoController@updateEstadoProyecto');
            Route::put('/putUpdateProyecto', 'ProyectoController@update');
            Route::post('/postProyectoCancelar', 'ProyectoController@cancelProject');
            Route::post('/postProyectoFinalizar', 'ProyectoController@endProject');
            Route::put('/putAplicarEnProyecto', 'ProyectoxEstudianteController@aceptarRechazarEstudiante');
            Route::post('/storeProyecto', 'ProyectoController@store');
            Route::post('/postApplyStudent', 'Api\ProyectoController@postApplyStudent');
            Route::get('/proyecto/estudiantes', 'ProyectoxEstudianteController@estudiantesPorProyecto');
            // REUNION 
            Route::post('/sendMeetingMail', 'Api\ProyectoController@postSendMeetingEmails');     

            // ESTUDIANTES
            Route::get('/getAllStudents', 'Api\EstudianteController@getAllStudents');
            Route::get('/getPerfiles', 'Api\EstudianteController@getPerfiles');
            Route::put('/updateEstudiante', 'Api\EstudianteController@updateEstudiante');
        });
    });
});