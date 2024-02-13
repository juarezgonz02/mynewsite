<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Carrera;
use App\Proyecto;
use App\ProyectoXCarrera;
use App\ProyectoXEstudiante;
use Illuminate\Support\Facades\Validator;

class EstadisticasController extends Controller
{

    public function getEstadisticasGenerales(Request $request){
        if(!$request->ajax()) return redirect('/home');
        
        
        $validator = Validator::make($request->all(), [
            'idCarrera' => 'nullable|integer',
            'idFacultad' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            // Manejo personalizado de errores de validación
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $idFacultad = (int)$request->idFacultad;
        $idCarrera = (int)$request->idCarrera;

        

        
        


        // CANTIDAD DE ESTUDIANTES POR FACULTAD Y CARRERA
        if($idCarrera != null || $idCarrera != 0){
            $numeroDeEstudiantes = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('carrera.idCarrera', '=', $idCarrera)
                ->count();
                    
        }
        
        else{
            if($idFacultad != null || $idFacultad != 0){
                // $numeroDeEstudiantes = User::where('estado', '=', 1)->where('idRol','=', 2)->count();
                $numeroDeEstudiantes = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                    ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                    ->where('users.estado', '=', 1)
                    ->where('users.idRol','=', 2)
                    ->where('facultad.idFacultad', '=', $idFacultad)
                    ->count();
                
                
                
            }       
            else{
                $numeroDeEstudiantes = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->count();
                
            }
        }


        

        // students enroled in projects by faculty and career
        // ESTUDIANTES ACTIVOS EN PROYECTOS
        $numeroDeEstudiantesInscritos = -1;

        if($idCarrera != null || $idCarrera != 0){

            $numeroDeEstudiantesInscritos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('carrera.idCarrera', '=', $idCarrera)
                ->where('proyectoxestudiante.estado', '=', 1)
                ->count();
            
        }
        else{
            if($idFacultad != null || $idFacultad != 0){
    
                $numeroDeEstudiantesInscritos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('facultad.idFacultad', '=', $idFacultad)
                ->where('proyectoxestudiante.estado', '=', 1)
                ->count();
                
            }
            else{
                $numeroDeEstudiantesInscritos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                    ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                    ->join('proyectoxestudiante', 'users.idUser', '=', 'proyectoxestudiante.idUser')
                    ->where('users.estado', '=', 1)
                    ->where('users.idRol','=', 2)
                    ->where('proyectoxestudiante.estado', '=', 1)
                    ->count();
                
            }
        }




        // TOTAL DE PROYECTOS ACTIVOS
        $totalNumeroDeProyectosActivos = -1;

        if($idCarrera != null || $idCarrera != 0){
            $totalNumeroDeProyectosActivos = Proyecto::join('proyectoxcarrera', 'proyecto.idProyecto', '=', 'proyectoxcarrera.idProyecto')
            ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
            ->where('carrera.idCarrera', '=', $idCarrera)
            ->groupBy('proyectoxcarrera.idCarrera')->count();
        }
        else{
            if($idFacultad != null || $idFacultad != 0){
                $totalNumeroDeProyectosActivos = Proyecto::join('proyectoxcarrera', 'proyecto.idProyecto', '=', 'proyectoxcarrera.idProyecto')
                ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
                ->where('carrera.idFacultad', '=', $idFacultad)
                ->groupBy('proyectoxcarrera.idCarrera')->count();
            }
            else{
                $totalNumeroDeProyectosActivos = Proyecto::count();
            }

        }



        if($idCarrera != null || $idCarrera != 0){
            $numeroDeProyectosEnCurso = Proyecto::
            join('proyectoxcarrera', 'proyecto.idProyecto', '=', 'proyectoxcarrera.idProyecto')
            ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
            ->where('estado_proyecto', '=', 'En curso')
            ->where('carrera.idFacultad', '=', $idFacultad)
            ->where('carrera.idCarrera', '=', $idCarrera)->count();
        }        
        else{
            if($idFacultad != null || $idFacultad != 0){
                $numeroDeProyectosEnCurso = ProyectoxCarrera::join('proyecto','proyectoxcarrera.idProyecto' , '=','proyecto.idProyecto' )
                    ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
                    ->where('proyecto.estado_proyecto', '=', 'En curso')
                    ->where('carrera.idFacultad', '=', $idFacultad)
                    ->groupBy('proyectoxcarrera.idCarrera')->count();
                
            }
            
            else{
                $numeroDeProyectosEnCurso = Proyecto::
            where('estado_proyecto', '=', 'En curso')->count();
                         }
        }
        
        // PROYECTOS TERMINADOS
        // $numeroDeProyectosTerminados = Proyecto::where('estado_proyecto', '=', 'Terminado')->count();
        $numeroDeProyectosTerminados = -1;

        if($idCarrera != null || $idCarrera != 0){
            $numeroDeProyectosTerminados = Proyecto::
            join('proyectoxcarrera', 'proyecto.idProyecto', '=', 'proyectoxcarrera.idProyecto')
            ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
            ->where('estado_proyecto', '=', 'Finalizado')
            ->where('carrera.idFacultad', '=', $idFacultad)
            ->where('carrera.idCarrera', '=', $idCarrera)->count();
        }        
        else{
            if($idFacultad != null || $idFacultad != 0){
                $numeroDeProyectosTerminados = ProyectoxCarrera::join('proyecto','proyectoxcarrera.idProyecto' , '=','proyecto.idProyecto' )
                    ->join('carrera', 'proyectoxcarrera.idCarrera', '=', 'carrera.idCarrera')
                    ->where('proyecto.estado_proyecto', '=', 'Finalizado')
                    ->where('carrera.idFacultad', '=', $idFacultad)
                    ->groupBy('proyectoxcarrera.idCarrera')->count();
                
            }
            
            else{
                $numeroDeProyectosTerminados = Proyecto::
            where('estado_proyecto', '=', 'Finalizado')->count();
                         }
        }




        return [
            'numeroDeEstudiantes' => $numeroDeEstudiantes,
            'numeroDeProyectosEnCurso' =>  $numeroDeProyectosEnCurso,
            'numeroDeProyectosTerminados' => $numeroDeProyectosTerminados,
            'numeroDeEstudiantesInscritos' => $numeroDeEstudiantesInscritos,
            'totalNumeroDeProyectosActivos' => $totalNumeroDeProyectosActivos,
            'idCarrera' => $idCarrera,
            'idFacultad' => $idFacultad,
        ];

    }

}


