<?php

namespace App\Http\Controllers\Api;

use App\Carrera;
use App\Facultad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarreraController extends Controller
{
    /**
     * Responde con todas las carreras de la tabla
     */
    public function getCarreras() {
        $carreras = Carrera::orderBy('nombre')->get();
        return response()->json($carreras);
    }

    /**
     * Responde con todas las carreras que pertenecen al ID de Facultad que se recibe
     *
     * @param $idFacultad
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarrerasPorFacultad($idFacultad){
        $carreras = Carrera::where('idFacultad', '=', $idFacultad)->get();

        return response()->json($carreras);
    }
    
    /**
     * Responde con todas las carreras y facultades
     *
     * @param $idFacultad
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarrerasYFacultades(){
        $carreras = Carrera::get();
        $facultades = Facultad::get();

        return [
            'data'=> [
                'carreras' => $carreras,
                'facultades' => $facultades
            ]
        ];
    }
}
