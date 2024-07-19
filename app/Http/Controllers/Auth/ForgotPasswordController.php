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
use App\Jobs\SendCambiarContraMail;

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
                return redirect('/')->withErrors(['verified'=>'Cuenta ya verificada']);
            }
        }catch(\Throwable $e){
            return redirect('/');
        }
    }

    //se verifica la cuenta y redirige al login
    public function verificarUsuario(Request $request, $email){

        $this->validatePassword($request);
        try{
            $user = User::whereCorreo($email)->first();
            
            if($user -> verificado == 1){
                return redirect('/')->withErrors(['verified'=>'No se aplicaron los cambios, usuario ya verificado']);
            }
            
            $token = PasswordResetToken::where('token', strtoupper(trim($request->token)))->firstOrFail();

            if($token -> idUser != $user -> idUser){
                return back()->withErrors(['token' => trans('auth.token_error')]);
            }else{
                $user->update(['password'=> $request->contrasena, 'verificado' => 1]);
            }
            return redirect('/')->withErrors(['verified'=>'Se verificó con exito']);
        }catch(\Throwable $e){
            return back()->withErrors(['token' => trans('auth.token_error')]);
        }
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
                $token = PasswordResetToken::where('idUser', $user->idUser)->where('expires_at', '>', Carbon::now())->first();

                if($token == null){
                    $token = PasswordResetToken::create([
                        'idUser' => $user->idUser,
                        'token' => strtoupper(Str::random(10)),
                        'expires_at' => Carbon::now()->addHour(),
                    ]);
                    $this->sendEmail($user, $token);
                }
                return view('auth.enviadoCorreoContraOlvidada');
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

                $token = PasswordResetToken::where('idUser', $user->idUser)->where('expires_at', '>', Carbon::now())->first();
                

                if($token == null){
                    $token = PasswordResetToken::create([
                        'idUser' => $user->idUser,
                        'token' => strtoupper(Str::random(10)),
                        'expires_at' => Carbon::now()->addHour(),
                    ]);
                    $this->sendEmail($user, $token);
                }
                return view('auth.enviadoCorreoContraOlvidada');
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
    public function formularioOlvidoContrsenia($token){
        try{
            $pass_token = PasswordResetToken::where('token', $token)->with(['user'])->firstOrFail();
            
            if($pass_token -> expires_at < Carbon::now()){
                return view('auth.failContraOlvidada');
            }

            if($pass_token -> user -> ultima_fecha_contra == date('d-m-Y')){
                return view('auth.contraOlvidada')->withInput(request(['token']))->with(['user'=> $pass_token -> user])->withErrors([
                    'ya_cambio' => 'Ya cambio su contraseña hoy.'
                ]);
            }
            else {
                return view('auth.contraOlvidada')->with(['token'=>$token, 'user'=> $pass_token -> user]);
            }
        }
        catch (\Throwable $th) {
            return view('auth.failContraOlvidada');

        }
    }


    public function cambiarClave(Request $request) {
        $this->validatePassword($request);

        try{

            $token = PasswordResetToken::where('token', strtoupper(trim($request->token)))->firstOrFail();
            if(Carbon::now() > $token->expires_at) {
                return view('auth.failContraOlvidada');
            }
        
        } catch (\Throwable $th) {
            return view('auth.failContraOlvidada');
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

            $token -> update([
                'expires_at' => Carbon::now()->addMinutes(1)
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
        // Mail::send(
        //     'emails.cambiarContra',
        //     ['user' => $user, 'token' => $token->token],
        //     function($message) use ($user){
        //         $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
        //         $message->to($user->correo);
        //         $message->subject("Solicitud para cambiar contraseña.");
        //     }
        // );

        $emailDetails = [
            'email' => $user->correo,
            'token' => $token->token,
            'user' => $user
        ];

        SendCambiarContraMail::dispatch($emailDetails)->onConnection('database');
    }

    protected function validateMail(Request $request){
        $this->validate($request, [
            'carnet' => 'required|numeric|digits:8'
        ]);
    }

    protected function validatePassword(Request $request){
        $this->validate($request, [
            'token' => 'required|string',
            'contrasena' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/',
            'confirmar' => 'required|same:contrasena'
        ], [
            'token.required' => 'Revisa tu correo para obtener tu código',
            'contrasena.required' => 'Debes establecer una contraseña',
            'contrasena.regex' => 'La contraseña no cumple con el formato requerido',
            //'confirmar' => 'required|same:contraseña'
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
