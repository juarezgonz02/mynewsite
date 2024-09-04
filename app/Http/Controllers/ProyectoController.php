<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Proyecto;
use App\ProyectoxCarrera;
use App\Carrera;
use App\User;
use App\ProyectoxEstudiante;
use Mail;
use App\Jobs\SendReunionMail;
use App\Jobs\SendProyectoDesactivadoMail;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/home');
        $proyectos = Proyecto::where('estado','=','1')->orderByRaw('created_at DESC')->paginate(5);
        $cupos = ProyectoxEstudiante::select('estado', 'idProyecto', 'idUser')->get();
        for($i = 0; $i < count($proyectos); $i++){
            $proyectos[$i]->notificaciones = 0;
            for($j = 0; $j < count($cupos) ; $j++){
                if($proyectos[$i]->idProyecto == $cupos[$j]->idProyecto && $cupos[$j]->estado == '0'){
                    $proyectos[$i]->notificaciones = 1;
                    break;
                }
            }
        }
        return [
            'pagination' => [
                'total'         => $proyectos->total(),
                'current_page'  => $proyectos->currentPage(),
                'per_page'      => $proyectos->perPage(),
                'last_page'     => $proyectos->lastPage(),
                'from'          => $proyectos->firstItem(),
                'to'            => $proyectos->lastItem(),
            ],
            'proyectos' => $proyectos
        ];
    }

    public function proyectosNoDisponibles(Request $request)
    {
        if(!$request->ajax()) return redirect('/home');

        // where estado = 0 or estado_proyecto = 'Cancelado' or estado_proyecto = 'Finalizado'
        $proyectos = Proyecto::where('estado','=','0')->orWhere('estado_proyecto', '=', 'Cancelado')->orWhere('estado_proyecto', '=', 'Finalizado')->orderByRaw('created_at DESC')->paginate(5);

        return [
            'pagination' => [
                'total'         => $proyectos->total(),
                'current_page'  => $proyectos->currentPage(),
                'per_page'      => $proyectos->perPage(),
                'last_page'     => $proyectos->lastPage(),
                'from'          => $proyectos->firstItem(),
                'to'            => $proyectos->lastItem(),
            ],
            'proyectos' => $proyectos
        ];
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            //code...
            DB::transaction(function () use ($request) {
                
                $proyecto = new Proyecto();
                $proyecto->nombre = $request->nombre;
                $proyecto->estado = $request->estado;
                $proyecto->contraparte = $request->contraparte;
                $proyecto->cupos_act = $request->cupos_act;
                $proyecto->cupos = $request->cupos;
                $proyecto->estado_proyecto = $request->estado_proyecto;
                $proyecto->descripcion = $request->descripcion." ";
                $proyecto->perfil_estudiante = $request->perfil_estudiante;
                $proyecto->encargado = $request->encargado;
                $proyecto->fecha_inicio = $request->fecha_inicio;
                $proyecto->fecha_fin = $request->fecha_fin;
                $proyecto->horario = $request->horario;
                $proyecto->tipo_horas = $request->tipo_horas;
                $proyecto->correo_encargado = $request->correo_encargado;
                $proyecto->save();
                
                $arraycp = $request->carreraPerfil;
                
                if($request->aplicarTodasCarreras){
                    $this->aplicarTodasCarrerasAlProyecto($proyecto->idProyecto, $request->p_lim_inf, $request->p_lim_sup);
                }
                else{
                    for($i = 0; $i < count($arraycp); $i++){
                            $pxc = new ProyectoxCarrera();
                            $pxc->idProyecto = $proyecto->idProyecto;
                            $pxc->idCarrera = $arraycp[$i][0];
                            $pxc->limite_inf = $arraycp[$i][1];
                            $pxc->limite_sup = $arraycp[$i][2];
                            $pxc->save();
                    }
                }
                
                return response()->json('Proyecto creado exitosamente');
            });
        } catch (\Throwable $th) {
            // return response()->json('Crear Proyecto Fallo', 400);
            return response()->json(['Crear Proyecto Fallo'=>$th->getMessage()], 400);
        }
    }
    private function aplicarTodasCarrerasAlProyecto($idProyecto, $lim_inf, $lim_sup){
        $carreras = Carrera::where('estado', '=' ,1)->get();
        for($i = 0; $i < count($carreras); $i++){
            $pxc = new ProyectoxCarrera();
            $pxc->idProyecto = $idProyecto; 
            $pxc->idCarrera = $carreras[$i]->idCarrera;
            $pxc->limite_inf = $lim_inf;
            $pxc->limite_sup = $lim_sup;
            $pxc->save();
        }
    }
        private function todasLasCarreras(int $idProyecto, array $options){
            $carreras = Carrera::all();
            for($i = 0; $i < count($carreras); $i++){
                if($options[0] == -1 || ($options[0] == -2 && ($carreras[$i]->idCarrera != 3 && $carreras[$i]->idCarrera != 9 && $carreras[$i]->idCarrera != 10))){
                    $pxc = new ProyectoxCarrera();
                    $pxc->idProyecto = $idProyecto; 
                    $pxc->idCarrera = $carreras[$i]->idCarrera;
                    $pxc->limite_inf = $options[1];
                    $pxc->limite_sup = $options[2];
                    $pxc->save();
                }
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        try {

            
            
            //code...
            DB::transaction(function () use ($request) {
                
                $proyecto = Proyecto::findOrFail($request->idProyecto);
                $proyecto->contraparte = $request->contraparte;
                $proyecto->cupos = $request->cupos;
                $proyecto->estado_proyecto = $request->estado_proyecto;
                $proyecto->descripcion = $request->descripcion." ";
                $proyecto->perfil_estudiante = $request->perfil_estudiante;
                $proyecto->encargado = $request->encargado;
                $proyecto->fecha_inicio = $request->fecha_inicio;
                $proyecto->fecha_fin = $request->fecha_fin;
                $proyecto->horario = $request->horario;
                $proyecto->nombre = $request->nombre;
                $proyecto->tipo_horas = $request->tipo_horas;
                $proyecto->correo_encargado = $request->correo_encargado;
                $proyecto->estado = $request->estado;
                $proyecto->save();
                
                ProyectoxCarrera::where('idProyecto', '=', $request->idProyecto)->delete();
                
                $arraycp = $request->carreraPerfil;
                if($request->aplicarTodasCarreras){
                    $this->aplicarTodasCarrerasAlProyecto($proyecto->idProyecto, $request->p_lim_inf, $request->p_lim_sup);
                }
                else{
                    for($i = 0; $i < count($arraycp); $i++){
                            $pxc = new ProyectoxCarrera();
                            $pxc->idProyecto = $proyecto->idProyecto;
                            $pxc->idCarrera = $arraycp[$i][0];
                            $pxc->limite_inf = $arraycp[$i][1];
                            $pxc->limite_sup = $arraycp[$i][2];
                            $pxc->save();
                    }
                }
                return response()->json('Proyecto actualizado exitosamente');
            });
            
        } catch (\Throwable $th) {
            return response()->json(['Fallo al actualizar'=>$th->getMessage()], 400);
        }
}

public function state(Request $request)
{
    if(!$request->ajax()) return redirect('/home');
    $proyecto = Proyecto::findOrFail($request->idProyecto);
    $proyecto->estado = $request->estado;
        $proyecto->estado_proyecto = $request->estado_proyecto;
        $proyecto->save();

        
        $users = User::join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
        ->join('proyecto', 'proyecto.idProyecto', '=', 'proyectoxestudiante.idProyecto')
        ->select('users.correo', 'proyecto.nombre')
        ->where('proyectoxestudiante.idProyecto', '=', $request->idProyecto)
        ->where('proyecto.estado_proyecto', '==', 'Cancelado')->get();
        if(count($users) > 0){
            $mailArray = [];
            for($i=0; $i<count($users); $i++){
                $mailArray[$i] = $users[$i]->correo;
            }
            $this->sendEmail($mailArray, $users[0]);
        }
    }

    public function sendEmail($mails, $nombre){
        // Mail::send(
        //     'emails.proyectoDesactivado',
        //     ['mails' => $mails, 'nombre'=> $nombre],
        //     function($message) use ($mails, $nombre){
        //         $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
        //         $message->bcc($mails);
        //         $message->subject("Actualización de proyecto de horas sociales: ". $nombre->nombre);
        //     }
        // );

        $emailDetails = [
            'nombre' => $nombre,
            'mails' => $mails
        ];

        SendProyectoDesactivadoMail::dispatch($emailDetails)->onConnection('database');

    }

    public function cuposActuales(Request $request){
        return Proyecto::select('proyecto.cupos_act', 'proyecto.cupos')->where('idProyecto', '=', $request->idProyecto)->first();
    }

    public function postSendMeetingEmails(Request $request) {
        $manager = $request -> encargado;
        $manager_mail = $request -> encargado_correo;
        $project = $request -> nombre_proyecto;
        $description = $request -> descripcion;
        $students = $request -> estudiantes;
        $place = $request -> lugar;
        $date = $request -> fecha;
        $hour = $request -> hora;


        // Envia a los estudiantes involucrados
       
            // Mail::send(
            //     'emails.reunion',
            //     ['nombre_proyecto' => $project, 'descripcion' => $description, 'lugar' => $place, 'fecha' => $date, 'hour' => $hour,'encargado' => $manager], 
            //     function($message) use ($students, $manager_mail){
            //         # TEST 
            //         $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
            //         foreach ($students as $student) {
            //             $message->to($student);
            //         }
            //         $message->cc($manager_mail);
            //         $message->subject("El encargado del proyecto solicitó una reunion.");
            //     }
            // );

            foreach ($students as $student) {
                $emailDetails = [
                    'email' => $student,
                    'manager_email' => $manager_mail,
                    'nombre_proyecto' => $project,
                    'descripcion' => $description,
                    'lugar' => $place,
                    'fecha' => $date,
                    'hour' => $hour,
                    'encargado' => $manager,
                    'manager_mail' => $manager_mail
                ];
    
                SendReunionMail::dispatch($emailDetails)->onConnection('database');
            }
            
            

        
    }
   
    public function getEstudianteProyecto(Request $request, $idEstudiante ){

        $proyectos = Proyecto::join('proyectoxestudiante', 'proyecto.idProyecto', '=', 'proyectoxestudiante.idProyecto')
                            ->join('users', 'users.idUser', '=', 'proyectoxestudiante.idUser')
                            ->select('proyecto.nombre', 'proyecto.descripcion', 'proyecto.encargado', 'proyecto.correo_encargado', 'proyecto.horario', 'proyecto.fecha_inicio', 'proyecto.fecha_fin', 'proyecto.tipo_horas', 'proyecto.contraparte', 'proyecto.cupos', 'proyecto.cupos_act', 'proyecto.estado', 'proyecto.idProyecto')
                            ->where('users.idUser', '=', $idEstudiante)
                        ->where('proyectoxestudiante.estado', '=', '1')
                            ->get();

        return response()->json($proyectos);
    }


    public function endProject(Request $request) {
        try {
            DB::transaction(function () use ($request) {

                $proyecto = Proyecto::findOrFail($request->idProyecto);
                $proyecto->estado_proyecto = 'Finalizado';
                $proyecto->estado = 0;
                $proyecto->save();

            // EstudianteXProyecto
            //     Estado 0: Pendiente
            //     Estado 1: Aceptado / En curso
            //     Estado 2: Rechazado
            //     Estado 3: Aceptado / Finalizado
            //     Estado 4: Aceptado / Cancelado
            $proyectoXEstudiante = ProyectoxEstudiante::where('idProyecto', '=', $request->idProyecto)->get();
            for($i = 0; $i < count($proyectoXEstudiante); $i++){
                if($proyectoXEstudiante[$i]->estado == 1){
                    $proyectoXEstudiante[$i]->estado = 3;
                    $proyectoXEstudiante[$i]->save();
                }else if($proyectoXEstudiante[$i]->estado == 0){
                    $proyectoXEstudiante[$i]->delete();
                }
            }
        });
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Error al finalizar el proyecto']);
        }

        return response()->json(['success' => true, 'message' => 'Proyecto finalizado exitosamente']);
    }

    public function cancelProject(Request $request){
        try{
            DB::transaction(function () use ($request) {

            $proyecto = Proyecto::findOrFail($request->idProyecto);
            $proyecto->estado_proyecto = 'Cancelado';
            $proyecto->estado = 0;
            $proyecto->save();

            // Al cancelar, se elimina el registro de estudiantes en el proyecto 
            $proyectoXEstudiante = ProyectoxEstudiante::where('idProyecto', '=', $request->idProyecto)->get();

            // EstudianteXProyecto
            //     Estado 0: Pendiente
            //     Estado 1: Aceptado / En curso
            //     Estado 2: Rechazado
            //     Estado 3: Aceptado / Finalizado
            //     Estado 4: Aceptado / Cancelado
            for($i = 0; $i < count($proyectoXEstudiante); $i++){
                if($proyectoXEstudiante[$i]->estado == 1){
                    $proyectoXEstudiante[$i]->estado = 4;
                    $proyectoXEstudiante[$i]->save();
                }else if($proyectoXEstudiante[$i]->estado == 0){
                    $proyectoXEstudiante[$i]->delete();
                }
            }
        });


        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Error al cancelar el proyecto', 'error' => $th->getMessage()	]);
        }

        return response()->json(['success' => true, 'message' => 'Proyecto cancelado exitosamente']);

    }


    public function deleteProject(Request $request){
        try{
            DB::transaction(function () use ($request) {

            

                // Al cancelar, se elimina el registro de estudiantes en el proyecto 
                $proyectoXEstudiante = ProyectoxEstudiante::where('idProyecto', '=', $request->idProyecto)->get();
                foreach($proyectoXEstudiante as $p){
                    // $p->estado = 2;
                    $p->delete();
                }

                $proyecto = Proyecto::findOrFail($request->idProyecto);
                // $proyecto->estado = 0;
                // $proyecto->save();
                $proyecto->delete();
           
            });

            
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el proyecto', 'error' => $th->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => 'Proyecto eliminado exitosamente'],200);
        

    }
}
