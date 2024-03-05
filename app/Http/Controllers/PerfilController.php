<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perfil;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfil = Perfil::all();
        return $perfil;
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
        /**
     * Permite actualizar el perfil de un usuario por medio de su ID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function updateMyProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'idUsuario' => 'required',
            'idPerfil'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }



        try {
            
            $idEstudiante = $request->idUsuario;
            
            $estudiante = User::where('idUser', '=', $idEstudiante)->firstOrFail();
                $estudiante->idPerfil = $request->idPerfil;
                $estudiante->save();
            // Se añade perfil para acutualizar localstorage de la app movil
            $estudianteres = User::where('idUser', '=', $idEstudiante)->with(['perfil'])->firstOrFail();
            return response()->json($estudianteres,200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
        // $idEstudiante = Auth()->idUser;

        // $estudiante = User::where('idUser', '=', $idEstudiante)->firstOrFail();
        //     $estudiante->idPerfil = $request->idPerfil;
        //     $estudiante->save();

        // return response()->json($estudiante);
        
    }

    
}
