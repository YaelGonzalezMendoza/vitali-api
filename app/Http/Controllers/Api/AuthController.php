<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
   public function register(Request $request)
{
    $request->validate([
        'nombre' => 'required',
        'apellido_paterno' => 'required',
        'apellido_materno' => 'required',
        'correo_electronico' => 'required|email|unique:users',
        'contrasenia' => 'required|min:6',
        'curp' => 'required',
        'fecha_nacimiento' => 'required|date',
        'id_rol' => 'required|in:1,2',
        'especialidad' => 'required_if:id_rol,2',
        'cedula_profesional' => 'required_if:id_rol,2|unique:medicos'
    ]);

    $user = User::create([
        'nombre' => $request->nombre,
        'apellido_paterno' => $request->apellido_paterno,
        'apellido_materno' => $request->apellido_materno,
        'correo_electronico' => $request->correo_electronico,
        'contrasenia' => $request->contrasenia,
        'curp' => $request->curp,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'id_rol' => $request->id_rol,
    ]);

    if ($request->id_rol == 2) {
        Medico::create([
            'user_id' => $user->id,
            'especialidad' => $request->especialidad,
            'cedula_profesional' => $request->cedula_profesional,
        ]);
    }

    return response()->json([
        'message' => 'Registro exitoso',
        'user' => $user
    ], 201);
}

    public function login(Request $request)
    {
        $user = User::where('correo_electronico', $request->correo_electronico)
                    ->where('contrasenia', $request->contrasenia)
                    ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user
        ], 200);
    }
public function solicitarReset(Request $request)
{
    $request->validate([
        'correo_electronico' => 'required|email|exists:users'
    ]);

    $token = Str::random(6);

    DB::table('password_resets')->updateOrInsert(
        ['correo_electronico' => $request->correo_electronico],
        [
            'token' => $token,
            'created_at' => now()
        ]
    );

    return response()->json([
        'message' => 'Token generado',
        'token' => $token // simulamos envío por correo
    ], 200);
}
    public function confirmarReset(Request $request)
{
    $request->validate([
        'correo_electronico' => 'required|email',
        'token' => 'required',
        'nueva_contrasenia' => 'required|min:6'
    ]);

    $registro = DB::table('password_resets')
    ->where('correo_electronico', $request->correo_electronico)
    ->where('token', $request->token)
    ->where('created_at', '>=', now()->subMinutes(10))
    ->first();

    if (!$registro) {
        return response()->json([
            'message' => 'Token inválido'
        ], 400);
    }

    $user = User::where('correo_electronico', $request->correo_electronico)->first();
    $user->contrasenia = $request->nueva_contrasenia;
    $user->save();

    DB::table('password_resets')
        ->where('correo_electronico', $request->correo_electronico)
        ->delete();

    return response()->json([
        'message' => 'Contraseña actualizada correctamente'
    ], 200);
}
}