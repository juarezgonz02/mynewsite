<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Carrera;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{



    public function addAdmin(Request $request){
        if(!$request->ajax()) return redirect('/home');
        // if user exists update role to admin, if not create user with admin role
        $user = User::where('correo', $request->correo)->first();
        if($user){
            $user->idRol = 1;
            $user->save();
        }else{
            $validator = $this->valid($request); 
            if ($validator -> fails()) {
                return response()->json($validator->messages(), 400);
            }

            $usuario = User::create([
                'nombres'               => strtoupper($request->nombre),
                'apellidos'             => strtoupper($request->apellido),
                'correo'                => $request->correo,
                'estado'                => 1,
                'genero'                => $request->genero,
                'verificado'            => 1,
                'ultima_fecha_contra'   => '1-1-2000',
                'ya_aplico_hoy'         => '1-1-2000',
                'idRol'                 => 1,
                'password'              => $request->contrasena,
                'api_token'             => $this->generarApiToken()
            ]);
        }

        
    }

    public function deleteAdminUser(Request $request) {
        if(!$req->ajax()) return redirect('/home');
        

        $deletingId = $request->query("id");

        $user = Auth()->user();

        $found_user = User::find($deletingId);

        if(!$found_user || $deletingId == $user->idUser){
            return response()->json([
                'message'=>'id invalido'
            ], 404);;
        }
        
        $found_user->delete();

        return response()->json([
            'message'=>'Eliminado'
        ], 200);;

    } 

    public function getAllAdmins(Request $request) {
        
        $admins = User::select("nombres", "apellidos", "correo")->where("idRol", "=", "1")->get();
        
        return [
            'users' => $admins
        ];
    }

    private function valid(Request $request){
        $validator = Validator::make($request->all(), [
            'correo' => 'required|string|regex:/^.+@uca.edu.sv$/i|unique:users,correo',
            'nombre' => 'required|string|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'apellido' => 'required|string|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'genero' => 'required|in:F,M',
            'contrasena' => 'required|min:8'
        ]);

        return $validator;
    }
}