<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Carrera;
use App\Perfil;
use Illuminate\Support\Facades\Auth;
use App\ProyectoxEstudiante;

class UserController extends Controller
{
    public function getUser(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();

        if( $user -> idRol == 2 ){

            $user -> perfil = Perfil::find($user->idPerfil)->descripcion;
            $user -> carrera = Carrera::find($user->idCarrera)->nombre;
        }
        return $user;
    }

    public function yaAplico(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();
        if($user->ya_aplico_hoy == date('d-m-Y') ) {
            return 1;
        }
        else{
            return 0;
        }
    }

    public function estudiantePorCarnet(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $estudiante = User::where('correo', '=', $request->carnet . '@uca.edu.sv')->first();
        $carrera = null;
        if($estudiante){
            $carrera = Carrera::where('idCarrera', '=', $estudiante->idCarrera)->first();
        }
        return [$estudiante, $carrera];
    }

    public function actualizarEstudiante(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $estudiante = User::where('correo', '=', $request->carnet . '@uca.edu.sv')->first();
        $estudiante->idCarrera = $request->idCarrera;
        $estudiante->idPerfil = $request->idPerfil;
        $estudiante->save();
    }

    public static function ruta($flag){
        if($flag == 1){
            return env("API_HOST_ASSETS", "/public");
        }
        else{
            return 'https://uca.edu.sv/css-proyecto/public/';
        }
         
    }

    public function removerTimeOut(Request $request, $id_estudiante){
        if(!$request->ajax()) return redirect('/home');
        $estudiante = User::findOrFail($id_estudiante);
        $estudiante->timeout = null;
        $estudiante->save();
    }

    public function aplicarTimeOut(Request $request, $id_estudiante){
        if(!$request->ajax()) return redirect('/home');
        $estudiante = User::findOrFail($id_estudiante);
        // now + 30 days 
        $estudiante->timeout = date('Y-m-d', strtotime('+30 days'));
        $estudiante->save();
    }



    public function estadoAplicacionEstudiante(Request $request){

        if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();
        $id = $user->idUser;
        
        // Estudiante en proyecto activo
        // ->where('proyectoxestudiante.estado', '=', 1) hace referencia a proyectos Aceptados
        $proyectos = ProyectoxEstudiante::join('proyecto', 'proyecto.idProyecto', '=','proyectoxestudiante.idProyecto')
        ->join('users', 'proyectoxestudiante.idUser','=','users.idUser')
        ->select('proyecto.idProyecto', 'proyecto.nombre','proyecto.descripcion','proyecto.estado', 'proyecto.contraparte', 'proyecto.perfil_estudiante',
        'proyecto.tipo_horas', 'proyecto.cupos_act','proyecto.cupos', 'proyecto.horario', 'proyecto.encargado','proyecto.fecha_inicio','proyecto.fecha_fin','proyectoxestudiante.estado as estadoPxe')
        ->where('proyectoxestudiante.idUser','=', $user->idUser)
        ->where('proyectoxestudiante.estado', '=', 1)
        ->orderBy('proyecto.idProyecto', 'desc')->count();
        
        
        // $ya_aplico_hoy = $user->ya_aplico_hoy == date('d-m-Y')  ? 1 : 0;
        $fechaUsuario = \DateTime::createFromFormat('d-m-Y', $user->ya_aplico_hoy);
        $fechaActual = new \DateTime();
        
        $ya_aplico_hoy = $fechaUsuario->format('d-m-Y') == $fechaActual->format('d-m-Y') ? true : false;
        
        
        $activeProject = $proyectos > 0 ? true : false;
        
        $isTimeout = $user->timeout != null ? true : false;
        
        // return $ya_aplico_hoy;

        return(
            ['ya_aplico_hoy' => $ya_aplico_hoy,
            'activeProject' => $activeProject,
            'isTimeout' => $isTimeout]
        );

    }
}
