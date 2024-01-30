<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Carrera;
use App\Perfil;

class UserController extends Controller
{
    public function getUser(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();
        $user -> perfil = Perfil::find($user->idPerfil)->descripcion;
        $user -> carrera = Carrera::find($user->idCarrera)->nombre;
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
            return '/css-proyecto/public';
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
}
