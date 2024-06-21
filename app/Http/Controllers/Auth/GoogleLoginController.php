<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Facultad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Google\Auth\AccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleAPICallback(Request $request)
    {
        //code...
        // $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        // $payload = $client->verifyIdToken($request->credential);
        $auth = new AccessToken();
        $payload = $auth->verify($request->credential); 
        try {
            if ($payload) {
                $user_id = $payload['sub'];
                $user_email = $payload['email'];
                $user_name = $payload['given_name'];
                $user_family = $payload['family_name'];
                $user_hd = $payload['hd'];
                
                $userdb = User::where('correo', $user_email)->with(['rol', 'perfil', 'carrera.facultad'])->firstOrFail();
                Log::debug($userdb);
                if($userdb->estado == 0)
                {

                    return response()->json([
                        'carnet' => $user_email,
                        'nombres' => $user_email,
                        'apellidos' => $user_family,
                        'userId' => $user_id
                    ], 201); 

                }else{   
                    return response()->json([
                        'user' => $userdb,
                    ], 200); 
                }
                
            }
            else {
                return response()->json([
                    'message' => "Failing at google payload decoding",
                ], 500); 
            }
            
        } catch (\Throwable $th) {
            if(!isset($payload['hd'])){
                return response()->json([
                    'message' => 'Debes pertecener a uca.edu.sv',
                ], 404);
            }
            else {
                $user = $this->temporaty_register($payload);
                return response()->json([
                    'carnet' => $user->correo,
                    'nombres' => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'userId' => $payload['sub']
                ], 201); 
            }

        }
    }

    public function handleGoogleCallback(Request $request)
    {
        /*
        $googleUser = Socialite::driver('google')->stateless()->user();
        */
        
        // $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        // $payload = $client->verifyIdToken($request->credential);
        $auth = new AccessToken();
        $payload = $auth->verify($request->credential, ['audience' => env('GOOGLE_CLIENT_ID')]); 
        
        Log::debug($payload);
        try {
                if ($payload) {

                    $userid = $payload['sub'];
                    $user_email = $payload['email'];
                    
                    $user = User::where('correo', $user_email)->firstOrFail();
                    
                    if($user->estado == 0)
                    {
                        return redirect()->route('google.register', ['userId' => $payload['sub'], 'correo'=>$user->correo, 'nombres'=>$user->nombres, 'apellidos'=>$user->apellidos ]);            
                    }else{   
                        Auth::login($user);
                        return redirect("/home");
                    }
                    
                }
                else {
                    //   Log::debug("Invalid Token");
                    return redirect("/404");
                }
            }
        catch (\Throwable $th) {
            if(!isset($payload['hd'])){
                return redirect("/404");
            }else{

                $user = $this->temporaty_register($payload);
                return redirect()->route('google.register', ['userId' => $payload['sub'], 'correo'=>$user->correo, 'nombres'=>$user->nombres, 'apellidos'=>$user->apellidos ]);            
            }
        }

    }

    private function temporaty_register($token_payload){
        $nombre = $token_payload['given_name'];
        $apellido = $token_payload['family_name'];
        $email = $token_payload['email'];
        $tempPassword = $token_payload['sub'];
        $genero = "";

        

        return User::create([
            'nombres' => strtoupper($nombre),
            'apellidos' => strtoupper($apellido),
            'correo' => $email,
            'estado' => 0,
            'genero' => $genero,
            'verificado' => 0,
            'ultima_fecha_contra' => '1-1-2000',
            'ya_aplico_hoy' => '1-1-2000',
            'idRol' => 2,
            'password' => $tempPassword,
            'api_token' => $this->generarApiToken()
        ]);
        
        
    }

    public function showRegisterForm(Request $request){
        $facultades = Facultad::all();
        if(User::where('correo', $request->query('correo')) -> where('estado', 0) -> first()){
            return view('auth.google_register')
            ->with(['fact' => $facultades, 'userId' => $request->query('userId'), 'correo'=>$request->query('correo'), 'nombres'=>$request->query('nombres'), 'apellidos'=>$request->query('apellidos')]);
        }else{
            return redirect('404');
        }       
    }

    public function registrar(Request $request){

        $this->validData($request);
        $this->validCaptcha($request);
        
        if(Auth::attempt(['correo' => $request->correo, 'password' => $request->userId])){
            $this -> complete_user_data($request);

            return redirect()->intended('home');
        }else{
            return redirect('404');
        }
        
    }
    
    public function registrar_api(Request $request){
        $this->validData($request);

        if(Auth::attempt(['correo' => $request->correo, 'password' => $request->userId])){
            $user = $this -> complete_user_data($request);
            return response()->json(['user' => $user]) ;
        }else{
            return response()->json([
                'error' => 'Solicitud Invalida'
            ], 403); 
        }
        
    }

    private function validData(Request $request){
        $this->validate($request, [
            'perfil' => 'required|numeric|in:1,2,3,4,5,6',
            'carrera' => 'required|numeric|in:1,2,3,4,5,6',
            'genero' => 'required|string|in:F,M',
        ]);
    }
    private function validCaptcha(Request $request){
        $this->validate($request, [
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }

    private function complete_user_data(Request $request){
        $user = User::where('correo', $request->correo)->with(['rol', 'perfil', 'carrera.facultad'])->first();
        
        if($user->estado == 0){

            $user->update([
                'estado' => 1,
                'verificado' => 1,
                'genero' => $request->genero,
                'idPerfil' => $request->perfil,
                'idRol' => 2,
                'idCarrera' => $request->carrera,
            ]); 
            
            $user = User::where('correo', $request->correo)->with(['rol', 'perfil', 'carrera.facultad'])->first();
        }
        return $user;
    } 
}