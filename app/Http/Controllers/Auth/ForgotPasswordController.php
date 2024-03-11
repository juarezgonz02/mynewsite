<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\PasswordResetToken;
use App\User;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    //se muestra el formulario para verificar la cuenta, solo se puede usar 1 vez
    public function formularioVerificar($email){
        try{

            $user = User::whereCorreo($email)->firstOrFail();
            if($user->verificado == 0){
                return view('auth.verificarCuenta')->with(['user'=>$user]);
            }
            else{
                return redirect('/');
            }
        }catch(\Throwable $e){
            return redirect('/');
        }
    }

    //se verifica la cuenta y redirige al login
    public function verificarUsuario(Request $request, $email){
        $this->validatePassword($request);

        $user = User::whereCorreo($email)->first();
        $user->update(['password'=> $request->contraseña, 'verificado' => 1]);
        return redirect('/');
    }

    //Se muestra el formulario para solicitar nueva contraseña
    public function formularioEnviarCorreoContraOlvidada(Request $request){
        return view('auth.enviarCorreoContraOlvidada');
    }

    //Se envía al correo ingresado un link para cambiar la contraseña
    public function enviarCorreoContraOlvidada(Request $request){
        try{

        
        if(strpos($request->carnet, '@') == true){
            $user = User::whereCorreo($request->carnet)->firstOrFail();
            if($user == null){
                return back()
                ->withErrors(['correo_inexistente' => trans('auth.cuenta_inexistente')])
                ->withInput(request(['carnet']));
            }elseif($user->verificado == 0 && $user != null){
                return back()
                ->withErrors(['no_verificado' => trans('auth.carnet_no_verificado')])
                ->withInput(request(['carnet']));
            }
            elseif($user->ultima_fecha_contra == date('d-m-Y')){
                return back()
                ->withErrors(['cambio_fecha' => trans('auth.ya_cambio_contra')])
                ->withInput(request(['carnet'])); 
            }
            else{
                $token = PasswordResetToken::create([
                    'idUser' => $user->idUser,
                    'token' => strtoupper(Str::random(5)),
                    'expires_at' => Carbon::now()->addHour(),
                ]);

                $this->sendEmail($user, $token);
                return redirect('/cambiar_contra_olvidada/'.$request->carnet);
            }
        }
        else{
            $this->validateMail($request);
            $email = $request->carnet . "@uca.edu.sv";
            $user = User::whereCorreo($email)->firstOrFail();
            
            if($user == null) {
                return back()
                ->withErrors(['correo_inexistente' => trans('auth.carnet_inexistente')])
                ->withInput(request(['carnet']));
            }
            elseif($user->verificado == 0 && $user != null){
                return back()
                ->withErrors(['no_verificado' => trans('auth.carnet_no_verificado')])
                ->withInput(request(['carnet']));
            }
            elseif($user->ultima_fecha_contra == date('d-m-Y')){
                return back()
                ->withErrors(['cambio_fecha' => trans('auth.ya_cambio_contra')])
                ->withInput(request(['carnet'])); 
            }
            else{
                $token = PasswordResetToken::create([
                    'idUser' => $user->idUser,
                    'token' => strtoupper(Str::random(5)),
                    'expires_at' => Carbon::now()->addHour(),
                ]);

                $this->sendEmail($user, $token);
                return redirect('/cambiar_contra_olvidada/'.$email);
            }

           
        }
        }
        catch(\Throwable $e){
            return back()
                ->withErrors(['correo_inexistente' => trans('auth.carnet_inexistente')])
                ->withInput(request(['carnet'])); 
        }
    }

    //Se muestra el formulario para cambiar la contraseña olvidada
    public function formularioOlvidoContrsenia($email){
        try{

            $user = User::whereCorreo($email)->firstOrFail();
            if($user->ultima_fecha_contra == date('d-m-Y')){
                return view('auth.contraOlvidada')->with(['user'=>$user])->withErrors([
                    'ya_cambio' => 'Ya cambio su contraseña hoy.'
                ]);
            }
            else {
                return view('auth.contraOlvidada')->with(['user'=>$user]);
            }
        }catch(\Throwable $e){
            return view('404');
        }
    }


    public function cambiarClave(Request $request) {
        $validator = Validator::make($request->all(), [
            'token'       => 'required|string',
            'contrasena'  => 'required|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/',
            'confirmar'   => 'required|same:contrasena'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
        }

        try{

            $token = PasswordResetToken::where('token', $request->token)->firstOrFail();
            if(Carbon::now() > $token->expires_at) {
                return back()->withErrors([
                    'error' => 'Token venció'
                ]);
            }
        
        } catch (\Throwable $th) {
            return back()->withErrors([
                'codigo' => 'Código Invalido.'
            ]);
        }
            
        try{

            $usuario = User::where('idUser', '=', $token->idUser)->firstOrFail();
            
            if(date('d-m-Y') === $usuario->ultima_fecha_contra) {
                return back()->withErrors([
                    'ya_cambio' => 'Ya cambio su contraseña hoy.'
                ]);
            }
            
            $usuario->update([
                'password'      => $request->contrasena,
                'verificado'    => 1,
                'ultima_fecha_contra' => date('d-m-Y')
            ]);
            
            return back()->with([
                'message' => 'Se cambió la clave exitosamente.',
            ]); 
        } catch (\Throwable $th) {
            return back()->withErrors([
                'usuario' => 'algo salio mal.'
            ]);
        }
        }

    //Función que envía correos
    public function sendEmail($user, $token){
        Mail::send(
            'emails.cambiarContra',
            ['user' => $user, 'token' => $token->token],
            function($message) use ($user){
                $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
                $message->to($user->correo);
                $message->subject("Solicitud para cambiar contraseña.");
            }
        );
    }

    protected function validateMail(Request $request){
        $this->validate($request, [
            'carnet' => 'required|numeric|digits:8'
        ]);
    }

    protected function validatePassword(Request $request){
        $this->validate($request, [
            'contraseña' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/',
            'confirmar' => 'required|same:contraseña'
        ]);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
