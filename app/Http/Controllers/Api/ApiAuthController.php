<?php

namespace App\Http\Controllers\Api;

use Mail;
use App\User;
use Carbon\Carbon;
use App\PasswordResetToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmailJob;
use App\Jobs\SendCambiarContraMail;
class ApiAuthController extends Controller
{
    /**
     * Recibe el correo completo (incluyendo el @uca.edu.sv) y contrasena del usuario
     * Lo autentica y retorna el usuario con su api token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo'    => 'required|string',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $usuario = User::where('correo', '=', $request->correo)->with(['rol', 'perfil', 'carrera.facultad'])->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {

            if(!$usuario->api_token) {
                $usuario->api_token = $this->generarApiToken();
                $usuario->save();
            }

            return response()->json([
                'user' => $usuario,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Correo o clave incorrecta.'
            ], 400);
        }
    }

    /**
     * Obtiene los datos del request y crea un nuevo usuario
     * el correo debe incluir @uca.edu.sv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|string|email|unique:users,correo',
            'nombres' => 'required|string|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'apellidos' => 'required|string|regex:/([a-zA-ZñÑáéíóúÁÉÍÓÚ])+((\s*)+([a-zA-ZñÑáéíóúÁÉÍÓÚ]*)*)+$/',
            'genero' => 'required|in:F,M',
            'carrera' => 'required',
            'perfil' => 'required|numeric|in:1,2,3,4,5,6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // Iniando transaccion
        DB::beginTransaction();

        $usuario = User::create([
            'nombres'               => strtoupper($request->nombres),
            'apellidos'             => strtoupper($request->apellidos),
            'correo'                => $request->correo,
            'estado'                => 1,
            'genero'                => $request->genero,
            'verificado'            => 0,
            'ultima_fecha_contra'   => '1-1-2000',
            'ya_aplico_hoy'         => '1-1-2000',
            'idRol'                 => 2,
            'idPerfil'              => $request->perfil,
            'idCarrera'             => $request->carrera,
            'password'              => bcrypt('temporal'),
            'api_token'             => $this->generarApiToken()
        ]);

        // Generando tokens
            $token = PasswordResetToken::create([
                'idUser' => $usuario->idUser,
                'token' => strtoupper(Str::random(5)),
                'expires_at' => Carbon::now(),
            ]);
            $usuario -> utoken = $token; 



        try{
            // Mail::send(
            //     'emails.verificar',
            //     ['user' => $usuario,],
            //     function($message) use ($usuario){
            //         $message->from("automatic.noreply.css@gmail.com", "Centro de Servicio Social");
            //         $message->to($usuario->correo);
            //         $message->subject("Solicitud de creación de cuenta.");
            //     }
            // );

            $emailDetails = [
                'email' => $usuario->correo,
                'subject' => 'Solicitud de creación de cuenta.',
                'user' => $usuario
            ];
            SendEmailJob::dispatch($emailDetails)->onConnection('database');

        }
        catch (\Throwable $th) {
            
            DB::rollback();
            
            return response()->json([
                'message' => 'Error al enviar correo de verificación.',
            ], 500);
        }
        
        DB::commit();
        return response()->json([
            'user' => $usuario,
        ], 200);
    }

    /**
     * Recibe el correo completo (incluyendo el @uca.edu.sv) y genera un PasswordResetToken
     * Luego se envia correo con el token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function olvideClave(Request $request) {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|string|email|exists:users,correo',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $usuario = User::where('correo', '=', $request->correo)->firstOrFail();
        if($usuario->ultima_fecha_contra == date('d-m-Y')) {
            return response()->json([
                'error' => trans('auth.ya_cambio_contra')
            ], 200);
        } else
        
        {
            $token = PasswordResetToken::where('idUser', $usuario->idUser)->where('expires_at', '>', Carbon::now())->first();
            
            if($token == null){
                $token = PasswordResetToken::create([
                    'idUser' => $usuario->idUser,
                    'token' => strtoupper(Str::random(5)),
                    'expires_at' => Carbon::now()->addHour(),
                ]);

                
                
                $emailDetails = [
                'email' => $usuario->correo,
                'user' => $usuario,
                'token' => $token->token
                ];


                SendCambiarContraMail::dispatch($emailDetails)->onConnection('database');
                
            }
                    
            return response()->json([
                'message' => 'Solicitud de cambio de clave recibida, revise su correo electrónico.',
            ], 200);
        }
    }

    public function cambiarClave(Request $request) {
        $validator = Validator::make($request->all(), [
            'token'             => 'required|string',
            'clave'             => 'required|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&^#])[A-Za-z\d@$!%*?&^#]{8,}$/',
            'confirmar_clave'   => 'required|same:clave'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        try{

            $token = PasswordResetToken::where('token', $request->token)->firstOrFail();
            if(Carbon::now() > $token->expires_at) {
                return response()->json([
                    'error' => 'Token venció'
                ], 400);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Código Invalido.',
            ], 404);
        }
            
        $usuario = User::where('idUser', '=', $token->idUser)->firstOrFail();
        $usuario->update([
            'password'      => $request->clave,
            'verificado'    => 1
        ]);

        return response()->json([
            'message' => 'Se cambió la clave exitosamente.',
        ], 200);
    }

    // -------------------------------------------------HELPERS-------------------------------------------------

    /**
     * Obtiene el correo del alumno, determina el carnet y en base a los ultimos 2 digitos determina el perfil del alumno
     *
     * @param $correo
     * @return false|int|string
     */
    private function determinarPerfilDeAlumno($correo) {
        $carnet = substr($correo, 0, strpos($correo, "@"));
        $fechaActual = date('y');
        $fechaIngresoEstudiante = substr($carnet, -2);
        $perfil = $fechaActual - $fechaIngresoEstudiante;

        return $perfil > 6 ? 6 : $perfil;
    }
}