<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('lobby');
    Route::post('/login', 'Auth\LoginController@authenticate')->name('login');

    Route::get('/carreraxfacultad', 'CarreraController@carreraPorFact')->name('carreraxfact');

    Route::get('/register_form', 'Auth\RegisterController@showForm');
    Route::post('/register', 'Auth\RegisterController@registrar')->name('registrar');   

    Route::get('/verificar_usuario/{correo}', 'Auth\ForgotPasswordController@formularioVerificar');  
    Route::post('/verificar_usuario/{correo}', 'Auth\ForgotPasswordController@verificarUsuario');

    Route::get('/contra_olvidada_form', 'Auth\ForgotPasswordController@formularioEnviarCorreoContraOlvidada');
    Route::post('/contra_olvidada_correo', 'Auth\ForgotPasswordController@enviarCorreoContraOlvidada')->name('olvido_contrasenia'); 

    Route::get('/cambiar_contra_olvidada/{correo}', 'Auth\ForgotPasswordController@formularioOlvidoContrsenia');  
    Route::post('/cambiar_contra_olvidada', 'Auth\ForgotPasswordController@cambiarClave');  
    
    Route::get('/oauth2Redirect', 'Auth\GoogleLoginController@redirectToGoogle')->name('google.redirect');
    Route::post('/oauth2Callback', 'Auth\GoogleLoginController@handleGoogleCallback')->name('google.callback');
    Route::get('/registrar_google', 'Auth\GoogleLoginController@showRegisterForm')->name('google.register');
    Route::post('/registrar_google', 'Auth\GoogleLoginController@registrar')->name('google.register_google');
});


Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', 'Auth\LoginController@home')->name('main');
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/get_user', 'UserController@getUser');
    Route::get('/perfil', 'PerfilController@index');

    Route::middleware(['Administrador'])->group(function () {
        Route::get('/todos_proyectos', 'ProyectoController@index');
        Route::get('/buscar_nombre', 'ProyectoController@buscar_nombre');
        Route::get('/buscar_filtros', 'BusquedaController@buscar_filtros');
        Route::get('/historial_proyectos', 'ProyectoController@proyectosNoDisponibles');
        Route::post('/proyecto/insertar', 'ProyectoController@store');
         // REUNION 
        Route::post('/sendMeetingMail', 'ProyectoController@postSendMeetingEmails');

        Route::get('/estudiante/{idEstudiante}/proyectos', 'ProyectoController@getEstudianteProyecto');

        Route::put('/proyecto/actualizar', 'ProyectoController@update'); 
        Route::put('/proyecto/estado', 'ProyectoController@state');
        
        Route::get('/proyectosxcarrera', 'ProyectosxCarreraController@proyectosPorCarreraEdit');
        Route::get('/estudiantesxproyecto', 'ProyectoxEstudianteController@estudiantesPorProyecto');
        Route::put('/aplicarestudiante', 'ProyectoxEstudianteController@aceptarRechazarEstudiante');
        Route::put('/rechazarestudiante', 'ProyectoxEstudianteController@rechazarEstudiante');
        Route::post('/aplicarporadmin', 'ProyectoxEstudianteController@aplicarPorAdmin');
        Route::get('/estudiante_por_carnet', 'UserController@estudiantePorCarnet');

        Route::post('/carreras/insertar', 'CarreraController@crearCarrera');
        Route::put('/carreras/inactivar', 'CarreraController@inactivarCarrera');
        Route::put('/carreras/actualizar', 'CarreraController@actualizarCarrera');
        Route::put('/estudiante/actualizar', 'UserController@actualizarEstudiante');
        Route::get('/cupos_actuales', 'ProyectoController@cuposActuales');
        Route::delete('/proyectos/{id_proyecto}/estudiante/{id_estudiante}', 'ProyectoxEstudianteController@removerEstudiante' );
        Route::patch('/estudiante/{id_estudiante}/remover-timeout', 'UserController@removerTimeOut' );
        Route::get('/estadisticas', 'EstadisticasController@getEstadisticasGenerales');
        Route::get('/solicitudes', 'ProyectoxEstudianteController@get_all_applications');
        
        Route::post('/users/admin/new', 'AdminUserController@createUser');
        Route::delete('/users/admin/delete', 'AdminUserController@deleteAdminUser');
        Route::get('/users/admin/all', 'AdminUserController@getAllAdmins');
        Route::post('/proyecto/cancelar', 'ProyectoController@cancelProject');
        Route::post('/proyecto/finalizar', 'ProyectoController@endProject');
        Route::post('/proyecto/eliminar', 'ProyectoController@deleteProject');

        Route::get('/estadisticas/dashboard', 'EstadisticasController@getDashboardData');
    });

    Route::middleware(['NormalUser'])->group(function () {
        
        Route::get('/ya_aplico', 'UserController@yaAplico');
        Route::get('/pxe_estudiante', 'ProyectoxEstudianteController@pxePorId');
        Route::get('/proyectos_aplicados', 'ProyectoxEstudianteController@proyectosAplicados');
        Route::get('/mi_carrera', 'CarreraController@miCarrera');
        Route::get('/proyectos_carrera', 'ProyectosxCarreraController@proyectosPorCarrera');

        Route::post('/proyecto/aplicar', 'ProyectoxEstudianteController@aplicar');
        Route::post('/proyecto/desaplicar', 'ProyectoxEstudianteController@deleteRow');
        Route::put('/estudiante/actualizar/perfil', 'PerfilController@updateMyProfile');
        Route::put('/estudiante/actualizar/carrera', 'PerfilController@updateMyCareer');
        Route::get('/estadoAplicacion', 'UserController@estadoAplicacionEstudiante');
        Route::get('/carrera', 'CarreraController@index');
        Route::get('/facultad', 'FacultadController@index');

    });
});

  

   
