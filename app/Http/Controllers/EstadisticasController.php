<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Carrera;
use App\Proyecto;
use App\ProyectoxCarrera;
use App\ProyectoxEstudiante;
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

        $estudiantes = $this->getGendersByFacultadCarrera($idFacultad, $idCarrera);

        return [
            'numeroDeEstudiantes' => $numeroDeEstudiantes,
            'numeroDeProyectosEnCurso' =>  $numeroDeProyectosEnCurso,
            'numeroDeProyectosTerminados' => $numeroDeProyectosTerminados,
            'numeroDeEstudiantesInscritos' => $numeroDeEstudiantesInscritos,
            'totalNumeroDeProyectosActivos' => $totalNumeroDeProyectosActivos,
            'idCarrera' => $idCarrera,
            'idFacultad' => $idFacultad,
            'estudiantes' => $estudiantes
        ];

    }

    public function getGendersByFacultadCarrera($idFacultad = null, $idCarrera = 0){
        if($idCarrera != null || $idCarrera != 0){
            $numeroDeEstudiantesMasculinos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('users.genero', '=', 'M')
                ->where('carrera.idCarrera', '=', $idCarrera)
                ->count();
                    
        }
        
        else{
            if($idFacultad != null || $idFacultad != 0){
                // $numeroDeEstudiantes = User::where('estado', '=', 1)->where('idRol','=', 2)->count();
                $numeroDeEstudiantesMasculinos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                    ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                    ->where('users.estado', '=', 1)
                    ->where('users.idRol','=', 2)
                    ->where('users.genero', '=', 'M')
                    ->where('facultad.idFacultad', '=', $idFacultad)
                    ->count();
                
                
                
            }       
            else{
                $numeroDeEstudiantesMasculinos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('users.genero', '=', 'M')
                ->count();
                
            }
        }

        if($idCarrera != null || $idCarrera != 0){
            $numeroDeEstudiantesFemeninos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('users.genero', '=', 'F')
                ->where('carrera.idCarrera', '=', $idCarrera)
                ->count();
                    
        }
        
        else{
            if($idFacultad != null || $idFacultad != 0){
                // $numeroDeEstudiantes = User::where('estado', '=', 1)->where('idRol','=', 2)->count();
                $numeroDeEstudiantesFemeninos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                    ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                    ->where('users.estado', '=', 1)
                    ->where('users.idRol','=', 2)
                ->where('users.genero', '=', 'F')
                    ->where('facultad.idFacultad', '=', $idFacultad)
                    ->count();
                
                
                
            }       
            else{
                $numeroDeEstudiantesFemeninos = User::join('carrera', 'users.idCarrera', '=', 'carrera.idCarrera')
                ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
                ->where('users.estado', '=', 1)
                ->where('users.idRol','=', 2)
                ->where('users.genero', '=', 'F')

                ->count();
                
            }
        }


        return [
            'masculinos' => $numeroDeEstudiantesMasculinos,
            'femeninos' => $numeroDeEstudiantesFemeninos,
        ];


    }

    public function getDashboardData(Request $request){
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
        $year = (int)$request->year ?? 2024;

        
        $estudiantes = $this->getGendersByFacultadCarrera($idFacultad, $idCarrera);
        $arrayEstudiantesPorCarrera = $this->getTotalStudentsGroupByCareer();
        $arrayEstudiantesRegistradorPorMes = $this->parseObjectToArrayYear($this->getStudentsRegisteredByMonth($year), $year);
        $arrayProyectosRegistradosPorMes = $this->parseObjectToArrayYear($this->getProjectsRegisteredByMonth($year), $year);
        $arrayProyectosRegistradosPorAnio = $this->getProjectsRegisteredByYear();
        $arrayEstudiantesRegistradorPorAnio = $this->getStudentsRegisteredByYear();

        return [
            'estudiantes' => $estudiantes,
            'estudiantesRegistradosPorMes' => $arrayEstudiantesRegistradorPorMes,
            'meses' => $this->getMonths($year),
            'estudiantesPorCarrera' => $arrayEstudiantesPorCarrera,
            'proyectosRegistradosPorMes' => $arrayProyectosRegistradosPorMes,
            'proyectosRegistradosPorAnio' => $arrayProyectosRegistradosPorAnio,
            'estudiantesRegistradosPorAnio' => $arrayEstudiantesRegistradorPorAnio,
        ];
    }



    // Arreglo con los estudiantes registrados por mes en un año en específico
    public function getStudentsRegisteredByMonth($year){
        $students = User::where('estado', '=', 1)
            ->where('idRol','=', 2)
            ->whereYear('created_at', '=', $year)
            ->get()
            ->groupBy(function($date) {
                return $date->created_at->format('m');
            });
        $studentsCount = [];
            
        
        foreach ($students as $key => $value) {
            $studentsCount[(int)$key] = count($value);
        }


        return $studentsCount;
    }

    public function getProjectsRegisteredByMonth($year){
        
        $projects = Proyecto::whereYear('created_at', '=', $year)
            ->get()
            ->groupBy(function($date) {
                return $date->created_at->format('m');
            });
        $projectsCount = [];
            
        
        foreach ($projects as $key => $value) {
            $projectsCount[(int)$key] = count($value);
        }
        return $projectsCount;
    }
    public function parseObjectToArrayYear($object, $year = null){
        $array = [];
        for($i = 0; $i < 12; $i++){ // Corrección: cambiar $i de 0 a 1 y <= 12 en lugar de < 12
            
            $total = $object[$i+1] ?? 0; // Obtener el total del mes correspondiente

            $array[$i] = $total; // Asignar el total al mes correspondiente
        }

        if($year == date('Y')){

            $currentMonth = date('m');
            $array = array_slice($array, 0, $currentMonth);
        }

        return $array;
    }


    public function getMonths($year = null){

        $months = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        // if year is current year then get months until current month
        if($year == date('Y')){

            $currentMonth = date('m');
            $months = array_slice($months, 0, $currentMonth);
        }



        return $months;	

    }


    public function getTotalStudentsGroupByCareer(){
        // if(!$request->ajax()) return redirect('/home');
        
        $students = User::where('estado', '=', 1)
            ->where('idRol','=', 2)
            ->get()
            ->groupBy('idCarrera')
            ->map(function ($item) {
                return count($item);
            });

        $careers = Carrera::all();

        $arrayCareers = [];
        $arrayTotalStudentsCareers = [];


        $careers->map(function($career) use ($students){
            $career->estudiantes = $students[$career->idCarrera] ?? 0;
            // $arrayCareers->array_push($career->nombre);
            // $arrayTotalStudentsCareers->array_push($students[$career->idCarrera] ?? 0);


        });

        




        return $careers;
        }


        public function getProjectsRegisteredByYear(){

            $projects = Proyecto::get()
                ->groupBy(function($date) {
                    return $date->created_at->format('Y');
                });
            $projectsCount = [];
                
            
            foreach ($projects as $key => $value) {
                $projectsCount[(int)$key] = count($value);
            }
            return $projectsCount;
        }


        public function getStudentsRegisteredByYear(){

            $students = User::where('estado', '=', 1)
                ->where('idRol','=', 2)
                ->get()
                ->groupBy(function($date) {
                    return $date->created_at->format('Y');
                });
            $studentsCount = [];
                
            
            foreach ($students as $key => $value) {
                $studentsCount[(int)$key] = count($value);
            }
            return $studentsCount;
        }

}


