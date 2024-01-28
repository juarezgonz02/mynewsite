<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProyectoxEstudiante;
use App\Proyecto;
use App\User;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\DB;


class ProyectoxEstudianteController extends Controller{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/home');
        $proyectoXestudiantes = ProyectoxEstudiante::all();
        return $proyectoXestudiantes;
    }
    public function proyectosAplicados(Request $request)
    {
        if(!$request->ajax()) return redirect('/home');
        $id = Auth()->user()->idUser;
        $proyectos = ProyectoxEstudiante::join('proyecto', 'proyecto.idProyecto', '=','proyectoxestudiante.idProyecto')
        ->join('users', 'proyectoxestudiante.idUser','=','users.idUser')
        ->select('proyecto.idProyecto', 'proyecto.nombre','proyecto.descripcion','proyecto.estado',
        'proyecto.tipo_horas', 'proyecto.cupos_act','proyecto.cupos', 'proyecto.horario', 'proyecto.encargado','proyecto.fecha_inicio','proyecto.fecha_fin','proyectoxestudiante.estado as estadoPxe')
        ->where('proyectoxestudiante.idUser','=', $id)
        ->orderBy('proyecto.idProyecto', 'desc')->get();
        
        return $proyectos;
    }

    public function pxePorId (Request $request) {
        if(!$request->ajax()) return redirect('/home');
        $req = $request->idUser;
        $pXe = ProyectoxEstudiante::query('SELECT * FROM proyectoxestudiante pxe WHERE pxe.idUser = :req')->get();
        return $pXe;
    }

    public function estudiantesPorProyecto(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $idProyecto = $request->idProyecto;
        
        $estudiantes = User::join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
        ->select('users.nombres', 'users.apellidos', 'users.correo', 'users.genero', 'users.idPerfil', 'users.idCarrera', 'proyectoxestudiante.estado', 'users.idUser')
        ->where('proyectoxestudiante.idProyecto', '=', $idProyecto)
        ->orderBy('proyectoxestudiante.estado')->get();
        return $estudiantes;
    }

    public function aceptarRechazarEstudiante(Request $request){
        $idProyecto = $request->idProyecto;
        $idUser = $request->idUser;
        $estado = $request->estado;

        $proXEst = ProyectoxEstudiante::where('proyectoxestudiante.idProyecto', '=', $idProyecto)
        ->where('proyectoxestudiante.idUser', '=', $idUser)->first();
        $proXEst->estado = $estado;
        $proXEst->save();

        $allProXEst = ProyectoxEstudiante::where('proyectoxestudiante.idUser', '=', $idUser)->get();
        foreach($allProXEst as $proXEst){
            if($proXEst->idProyecto != $idProyecto){
                $proXEst->estado = 2;
                $proXEst->save();
            }
        }

        if($estado == 1){
            $restarCupo = Proyecto::where('proyecto.idProyecto', '=', $idProyecto)->first();
            $restarCupo->cupos_act = $restarCupo->cupos_act + 1;
            $restarCupo->save();
        }
        
        // Eliminando el resto de solicitudes y actualizando cupos

        $pxe = ProyectoxEstudiante::where('proyectoxestudiante.idUser', '=', $idUser)->get();
        $allProyectosByEstudiante = Proyecto::join('proyectoxestudiante', 'proyecto.idProyecto', '=', 'proyectoxestudiante.idProyecto')
        ->select('proyecto.idProyecto', 'proyecto.cupos_act')
        ->where('proyectoxestudiante.idUser', '=', $idUser)->get();
        
        foreach($pxe as $p){
            if($p->idProyecto != $idProyecto){

                $p->delete();
            }
        }


        $mailData = User::join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
        ->join('proyecto', 'proyectoxestudiante.idProyecto', '=', 'proyecto.idProyecto')
        ->select('users.nombres', 'users.apellidos', 'users.correo','proyecto.encargado','proyecto.nombre')
        ->where('proyectoxestudiante.idUser', '=', $idUser)
        ->where('proyectoxestudiante.idProyecto', '=', $idProyecto)
        ->first();

        $this->sendEmailAceptadoRechazado($mailData, $estado);

        return response()->json(['message' => 'Proyecto actualizado']);
    }

    public function rechazarEstudiante(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $idProyecto = $request->idProyecto;
        $idUser = $request->idUser;

        $rechazarEstudiante = ProyectoxEstudiante::where('proyectoxestudiante.idProyecto', '!=', $idProyecto)
        ->where('proyectoxestudiante.idUser', '=', $idUser)->get();

        if($rechazarEstudiante != null){
            foreach($rechazarEstudiante as $rechazar){
                $rechazar->estado = 2;
                $rechazar->save();
            }
        }
        
    }

    public function aplicar(Request $request){
        $pXe = new ProyectoxEstudiante();
        $pXe->idProyecto = $request->idProyecto;
        $pXe->idUser = $request->idUser;
        $pXe->estado = $request->estado;
        $pXe->save();

        $user = Auth()->user();
        $user->ya_aplico_hoy = date('d-m-Y');
        $user->save();
        
        $proyecto = ProyectoxEstudiante::join('users', 'users.idUser', '=', 'proyectoxestudiante.idUser')
        ->join('proyecto', 'proyecto.idProyecto','=', 'proyectoxestudiante.idProyecto')
        ->join('carrera', 'carrera.idCarrera', '=', 'users.idCarrera')
        ->select('proyecto.correo_encargado', 'proyecto.encargado', 'proyecto.nombre', 'users.nombres', 'users.apellidos', 'users.correo', 'carrera.nombre AS n_carrera')
        ->where('users.idUser', '=', $request->idUser)
        ->where('proyecto.idProyecto','=', $request->idProyecto)->first();
        $this->sendEmail($proyecto, 1);

        return response()->json([
            'message' => 'Solicitud enviada'
        ]);
    }

    public function aplicarPorAdmin(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $verify = ProyectoxEstudiante::where('proyectoxestudiante.idProyecto', '=', $request->idProyecto)
        ->where('proyectoxestudiante.idUser', '=', $request->idUser)->first();
        if($verify == null){
            if(!$request->ajax()) return redirect('/home');
            $pXe = new ProyectoxEstudiante();
            $pXe->idProyecto = $request->idProyecto;
            $pXe->idUser = $request->idUser;
            $pXe->estado = $request->estado;
            $pXe->save();

            $restarCupo = Proyecto::where('proyecto.idProyecto', '=', $request->idProyecto)->first();
            $restarCupo->cupos_act = $restarCupo->cupos_act + 1;
            $restarCupo->save();

            $proyecto = ProyectoxEstudiante::join('users', 'users.idUser', '=', 'proyectoxestudiante.idUser')
            ->join('proyecto', 'proyecto.idProyecto','=', 'proyectoxestudiante.idProyecto')
            ->select('proyecto.encargado', 'proyecto.nombre', 'users.nombres', 'users.apellidos', 'users.correo')
            ->where('users.idUser', '=', $request->idUser)
            ->where('proyecto.idProyecto','=', $request->idProyecto)->first();
            $this->sendEmail($proyecto,2);
        }
        else{
            return "El estudiante ya está en el proyecto";
        }
    }

    public function sendEmail($user, $mailType){
        if($mailType == 1){
            Mail::send(
                'emails.estudianteAplico',
                ['user' => $user],
                function($message) use ($user){
                    $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                    $message->to($user->correo_encargado);
                    $message->subject("Aplicación de un estudiante en su proyecto.");
                }
            );
        }
        else{
            Mail::send(
                'emails.agregadoPorAdmin',
                ['user' => $user],
                function($message) use ($user){
                    $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                    $message->to($user->correo);
                    $message->subject("Actualización de ingreso a proyecto de horas sociales");
                }
            );
        }
        
    }

    public function sendEmailAceptadoRechazado($mailData, $estado){
        if($estado == 1){
            Mail::send(
                'emails.aceptado',
                ['data' => $mailData],
                function($message) use ($mailData){
                    $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                    $message->to($mailData->correo);
                    $message->subject("Estado de su aplicación. Proyecto: ". $mailData->nombre);
                }
            );
        }
        elseif($estado == 2){
            Mail::send(
                'emails.rechazado',
                ['data' => $mailData],
                function($message) use ($mailData){
                    $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                    $message->to($mailData->correo);
                    $message->subject("Estado de su aplicación. Proyecto: ". $mailData->nombre);
                }
            );
        }
    }

    public function deleteRow(Request $request){
        $idUser = $request->idUser;
        $idProyecto = $request->idProyecto;
        $pxe = ProyectoxEstudiante::where('idProyecto','=', $idProyecto)
        ->where('idUser','=', $idUser)->first();
        if($pxe->estado == 1){
            $p = Proyecto::where('idProyecto', '=', $pxe->idProyecto)->first();
            $p->cupos_act = $p->cupos_act-1;
            $p->save();
        }
        $pxe->delete();

        return response()->json([
            'message' => 'Alumno eliminado de proyecto'
        ]);
    }

    public function removerEstudiante(Request $request, $id_proyecto, $id_estudiante){
        $pxe = ProyectoxEstudiante::where('idProyecto','=', $id_proyecto)
        ->where('idUser','=', $id_estudiante)->first();
        if($pxe->estado == 1){
            $p = Proyecto::where('idProyecto', '=', $pxe->idProyecto)->first();
            $p->cupos_act = $p->cupos_act-1;
            $p->save();
        }
        $pxe->delete();

        // add 30 days timeout to user
        $user = User::where('idUser', '=', $id_estudiante)->first();
        $user->timeout = date('Y-m-d', strtotime('+30 days'));
        $user->save();

        

        return response()->json([
            'message' => 'Alumno eliminado de proyecto'
        ]);
    }
    public function get_all_applications(Request $request){
        

        $name = $request->query('nombre');
        
        $carrera = $request->query('carrera');
        

        $solicitudes = ProyectoxEstudiante::leftJoin('proyecto', 'proyecto.idProyecto', '=', 'proyectoxestudiante.idProyecto')
        ->leftJoin('users', 'users.idUser', '=', 'proyectoxestudiante.idUser')
        ->leftJoin('carrera', 'users.idCarrera', 'carrera.idCarrera')
        ->leftJoin('perfil', 'users.idPerfil', 'perfil.idPerfil')
        ->select("users.nombres as u_nombre", "users.apellidos as u_apellido", 'carrera.idCarrera as id_c' , 'carrera.nombre as carrera', 'perfil.descripcion as ano',"proyecto.*", "users.nombres", "users.apellidos", "users.correo" )
        ->where('proyectoxestudiante.estado', '=', '0');
        
        if($name != ""){
            $solicitudes = $solicitudes->where('proyecto.nombre', 'like', $name.'%');
        }

        if($carrera != "-1"){
            $solicitudes = $solicitudes->where('users.idCarrera', '=', $carrera);
        }    
    
        $solicitudes = $solicitudes->paginate(10);

        return [
            'pagination' => [
                'total'         => $solicitudes->total(),
                'current_page'  => $solicitudes->currentPage(),
                'per_page'      => $solicitudes->perPage(),
                'last_page'     => $solicitudes->lastPage(),
                'from'          => $solicitudes->firstItem(),
                'to'            => $solicitudes->lastItem(),
            ],
            'solicitudes' => $solicitudes
        ];
    }

}
