<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\ProyectoxCarrera;
use App\Carrera;
use App\User;
use App\ProyectoxEstudiante;
use Mail;

class BusquedaController extends Controller
{
    public function buscar_filtros(Request $request)
    {
        if(!$request->ajax()) return redirect('/home');

        $nombre = "";
        if($request->query('nombre') != ""){
            $nombre = $request->query('nombre');
        }
        
        $ncarrera = 'todas';
        if($request->query('carrera') != "-1" || $request->query('carrera') != "-2"){ 
            $ncarrera = $request->query('carrera');
        }else if($request->query('carrera') != "-2"){
            $ncarrera = 'todas_ex';
        }
        
        $orden = "created_at DESC";
        switch($request->query('orden')) {
            case 'antiguos': 
                $orden = "created_at DESC";
                break;

            case 'recientes': 
                $orden = "created_at ASC";
                break;
                
            case 'mas_cupos':
                $orden = 'cupos_act ASC'; 
                break;
                    
            case 'menos_cupos':
                $orden = 'cupos_act DESC';     
                break;    
            
            default: 
                $orden = 'created_at DESC';
        }
        
        $proyectos = $this->obtener_por_filtros($nombre, $ncarrera, $orden);

        $cupos = ProyectoxEstudiante::select('idProyecto')->where('estado', '=', '0')->get();
        for($i = 0; $i < count($proyectos); $i++){
            $proyectos[$i]->notificaciones = 0;
            for($j = 0; $j < count($cupos) ; $j++){
                if($proyectos[$i]->idProyecto == $cupos[$j]->idProyecto){
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
                'carrera'       => $ncarrera
            ],
            'proyectos' => $proyectos
        ];
    }

    private function obtener_por_filtros(string $nombre, string $ncarrera, string $orden)
    {
        if($ncarrera == "-1"){
            $proyectos = Proyecto::where('proyecto.nombre' ,'like', $nombre."%")->where('proyecto.estado', '=', '1');
        
        }else if ($ncarrera == "-2"){
            $proyectos = Proyecto::where('proyecto.nombre' ,'like', $nombre."%")->where('proyecto.estado', '=', '1');
        }else{
            $proyectos = ProyectoXCarrera::join('proyecto', 'proyecto.idProyecto', '=', 'proyectoxcarrera.idProyecto')
            ->leftJoin('carrera', 'carrera.idCarrera', '=', 'proyectoxcarrera.idCarrera')
            ->select("proyecto.*", "carrera.idCarrera")->where('carrera.idCarrera', '=', $ncarrera) ->where('proyecto.nombre', 'like', $nombre.'%');
        }

        $proyectos = $proyectos->orderByRaw('proyecto.'.$orden)->paginate(5);
        

        return $proyectos;
        
    }

}
    