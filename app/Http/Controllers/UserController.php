<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request){
        if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();
        return $user;
    }

    public function yaAplico(Request $request){
        //if(!$request->ajax()) return redirect('/home');
        $user = Auth()->user();
        if($user->ya_aplico_hoy == date('d-m-Y') ) {
            return 1;
        }
        else{
            return 0;
        }
    }
}
