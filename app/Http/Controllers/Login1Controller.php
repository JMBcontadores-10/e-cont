<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class Login1Controller extends Controller
{
    public function index()
    {
        if(Auth::check()){ //Retornamos a la vista home(modules) cuando esta en sesión aún. 
            return redirect()->route('modules'); 
        }else
        {
        return view('auth.login'); //retornamos al login siempre cuando el usuario no ha iniciado sesion aún
        }
    }

    public function login(Request $request)
    {

        if ($request->has('rfcC')) {

            $t = DB::table('clientes')
              //  ->select('tipo', 'password', 'Id_Conta','nombre')
                ->where('RFC', $request->rfcC)
                ->first();

            if (!$t == null) {
                if (Hash::check($request->passC, $t['password'])) {
                   // $ti = $t['tipo'];
                   // $co = $t['Id_Conta'];
                    $nombre=$t['nombre'];
                    Session::put('nombreU', $nombre);// session put para el nombre del contador
                   // Session::put('idConta', $co);
                   // Session::put('tipoU', $ti);


                    if (Session::get('tipoU') == '2') {


                        return view('auth.login');
                       // ->with('rfc', $request->rfcC);
                    } else{
                        return back();
                    }
                } else {
                    return back();
                }
            } else {
                return back();
            }
        } else {
            Session::put('tipoU', "3");
            return view('auth.login');
        }

    }

    public function store(Request $request)
    {

        $rfcCo = $request->RFC;

        echo $rfcCo;
        echo "<br>";

        $t = DB::table('clientes')
            ->select('tipo')
            ->where('RFC', $rfcCo)
            ->first();

        $ti = $t['tipo'];
        $nombre=$t['nombre'];


        Session::put('tipoU', $ti);
        Session::put('nombreU', $nombre);


        return view('auth.login')
            ->with('rfc', $rfcCo);
    }
}
