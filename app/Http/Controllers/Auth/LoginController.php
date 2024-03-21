<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Rules\Captcha;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
        
        if(strpos($request->carnet, '@') == true){
            $user = User::whereCorreo($request->carnet)->first();
            if($user == null){
                return back()
                ->withErrors(['email_inexistente' => trans('auth.carnet_inexistente')])
                ->withInput(request(['carnet']));
            }
            else{
                $this->validateAdmin($request);
                if($user->verificado == 0){
                    return back()
                    ->withErrors(['no_verified' => trans('auth.aun_no_verificado')])
                    ->withInput(request(['carnet']));
                }
                else{
                    $email = $request->carnet;
                    $psw = $request->contraseña;
                    if(Auth::attempt(['correo' => $email, 'password' => $psw])){
                        return redirect()->intended('home');
                    }
                    else{
                        return back()
                        ->withErrors(['contraseña' => trans('auth.failedPass')])
                        ->withInput(request(['carnet']));
                    }
                }
            }
            
        }
        else{
            $this->validateLogin($request);
            $email = $request->carnet . "@uca.edu.sv";
            $psw = $request->contraseña;
            $user = User::whereCorreo($email)->first();
            if($user == null){
                return back()
                ->withErrors(['email_inexistente' => trans('auth.carnet_inexistente')])
                ->withInput(request(['carnet']));
            }
            else{

                if($user->verificado == 0){
                    return back()
                    ->withErrors(['no_verified' => trans('auth.aun_no_verificado')])
                    ->withInput(request(['carnet']));
                }
                else{
                    if(Auth::attempt(['correo' => $email, 'password' => $psw])){
                        return redirect()->intended('home');
                    }
                    else{
                        return back()
                        ->withErrors(['contraseña' => trans('auth.failedPass')])
                        ->withInput(request(['carnet']));
                    }
                }
                
            }
        }
        
    }
    protected function validateLogin(Request $request){
        $this->validate($request, $rules = [
            'carnet' => 'required|numeric|digits:8',
            'contraseña' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ], $messages = [
            'g-recaptcha-response' => trans('auth.recaptcha'),
        ]);
    }

    protected function validateAdmin(Request $request){
        $this->validate($request, $rules = [
            'contraseña' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ], $messages = [
            'g-recaptcha-response' => trans('auth.recaptcha'),
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
