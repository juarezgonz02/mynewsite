<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Carrera;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carreras = Carrera::all();
        return $carreras;
    }

    public function miCarrera(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $id = Auth()->user()->idUser;
        $carrera = Carrera::join('users', 'users.idCarrera', '=','carrera.idCarrera')
        ->join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
        ->join('perfil' , 'perfil.idPerfil', '=', 'users.idPerfil')
        ->select('carrera.nombre as nombre_c', 'facultad.nombre as nombre_f', 'perfil.descripcion as anio_carrera', 'perfil.idPerfil', 'carrera.idCarrera')
        ->where('users.idUser','=', $id)->get();
        return $carrera;
    }

    public function carreraPorFact(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $carreras = Carrera::where('idFacultad', '=', $request->idFact)->get();
        return $carreras;
    }

    public function getCarrerasConFacultades(){
        $carreras = Carrera::join('facultad', 'carrera.idFacultad', '=', 'facultad.idFacultad')
            ->select('carrera.idCarrera', 'carrera.nombre as nombre_carrera', 'facultad.idFacultad', 'facultad.nombre as nombre_facultad')
            ->get();

        return $carreras;
    }

    public function crearCarrera(Request $request){
        try {
            DB::transaction(function () use ($request) {
                
                $carrera = new Carrera();
                $carrera->idFacultad = $request->idFacultad;
                $carrera->nombre = $request->nombre;
                $carrera->save();
                
                return response()->json('Carrera creada exitosamente');
            });
        } catch (\Throwable $th) {
            return response()->json(['Crear Carrera Falló'=>$th->getMessage()], 400);
        }
    }

    public function actualizarCarrera(Request $request){
        
        try {
            DB::transaction(function () use ($request) {
                
                $carrera = Carrera::findOrFail($request->idCarrera);
                $carrera->idFacultad = $request->idFacultad;
                $carrera->nombre = $request->nombre;
                $carrera->save();
                
                return response()->json('Carrera actualizada exitosamente');
            });
        } catch (\Throwable $th) {
            return response()->json(['Actualizar Carrera Falló'=>$th->getMessage()], 400);
        }
    }

    public function eliminarCarrera(Request $request, $idCarrera){
        $carrera = Carrera::where('idCarrera','=', $idCarrera);

        $carrera->delete();

        return response()->json(['success' => true, 'message' => 'Carrera eliminada exitosamente'],200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
