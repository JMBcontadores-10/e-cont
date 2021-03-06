<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Registro2Controller extends Controller
{
    public function index()
    {
        return view("auth.registro");
    }

    // Actualiza las contraseñas de los RFC proporcionados
    public function store(Request $request)
    {
        if ($request->has('RFC')) {
            User::where(['RFC' => $request->input('RFC')])->update(['password' => Hash::make($request->input('password'))]);
        }
        if ($request->has('RFC2')) {
            User::where(['RFC' => $request->input('RFC2')])->update(['password' => Hash::make($request->input('password2'))]);
        }
        if ($request->has('RFC3')) {
            User::where(['RFC' => $request->input('RFC3')])->update(['password' => Hash::make($request->input('password3'))]);
        }
        if ($request->has('RFC4')) {
            User::where(['RFC' => $request->input('RFC4')])->update(['password' => Hash::make($request->input('password4'))]);
        }
        if ($request->has('RFC5')) {
            User::where(['RFC' => $request->input('RFC5')])->update(['password' => Hash::make($request->input('password5'))]);
        }

        return back();
    }
}
