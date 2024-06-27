<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Estudiante;
use App\Facultad;
use App\PasswordResetToken;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Rules\Captcha;
use Swift_TransportException;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    private function formatStr($cadena) {
        $no_permitidas = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç",
        "Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas = array ("a","e","i","o","u","A","E","I","O","U","Ñ","A","E","I","O","U","a","e","i","o","u","c","C","a","e",
        "i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    public function showForm(){
        $facultades = Facultad::all();
        return view('auth.register')->with('fact', $facultades);
    }

    public function registrar(Request $request){
        $this->validator($request);
        
        $email = $request->carnet . "@uca.edu.sv";
        $user = User::whereCorreo($email)->first();
        if($user == null){
            $nombre = $this->formatStr($request->nombres);
            $apellido = $this->formatStr($request->apellidos);
            $carnet = $request->carnet;
            $genero = $request->genero;
            $carrera = $request->carrera;
            $perfil = $request->perfil;

            User::create([
                'nombres' => strtoupper($nombre),
                'apellidos' => strtoupper($apellido),
                'correo' => $email,
                'estado' => 1,
                'genero' => $genero,
                'verificado' => 0,
                'ultima_fecha_contra' => '1-1-2000',
                'ya_aplico_hoy' => '1-1-2000',
                'idRol' => 2,
                'idPerfil' => $perfil,
                'idCarrera' => $carrera,
                'password' => bcrypt('temporal'),
                'api_token' => $this->generarApiToken()
            ]);

            $user = User::whereCorreo($email)->first();
            $token = PasswordResetToken::create([
                'idUser' => $user->idUser,
                'token' => strtoupper(Str::random(5)),
                'expires_at' => Carbon::now(),
            ]);
            $user -> utoken = $token; 
            if(!$this->sendEmail($user)){
                $user->delete();
                return (500);
            }
            return redirect('/verificar_usuario/'.$email);
        }
        elseif($user != null && $user->verificado == 0){
            return back()
            ->withErrors(['email_existente' => trans('auth.aun_no_verificado')])
            ->withInput(request(['carnet', 'apellidos', 'nombres']));  
        }
        else{
            return back()
            ->withErrors(['email_existente' => trans('auth.ya_verificado')])
            ->withInput(request(['carnet', 'apellidos', 'nombres']));
        }
    }

    protected function validator(Request $request)
    {
        $this->validate($request, [
            'carnet' => 'required|numeric|digits:8',
            'nombres' => 'required|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'apellidos' => 'required|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'perfil' => 'required|numeric|in:1,2,3,4,5,6',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }

    public function sendEmail($user){
        try{
            Mail::send(
                'emails.verificar',
                ['user' => $user],
                function($message) use ($user){
                    $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                    $message->to($user->correo);
                    $message->subject("Solicitud de creación de cuenta.");
                }
            );
        }catch(Swift_TransportException $e){
            echo "Error al enviar correo electrónico de registro. Ponte en contacto con el administrador.";
            return false;
        }
        return true;
    }
}
