<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recordatorio;
use App\Models\User;

class RecordatorioController extends Controller
{
    // 🔹 LISTAR RECORDATORIOS POR USUARIO
    public function index($user_id)
    {
        $recordatorios = Recordatorio::where('user_id', $user_id)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return response()->json($recordatorios);
    }

    // 🔹 CREAR RECORDATORIO
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_hora'  => 'required|date',
        ]);

        $user = User::find($request->user_id);

        // 🔒 Solo pacientes pueden crear recordatorios
        if ($user->id_rol != 1) {
            return response()->json([
                'message' => 'Solo pacientes pueden crear recordatorios'
            ], 403);
        }

        $recordatorio = Recordatorio::create([
            'user_id'     => $request->user_id,
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_hora'  => $request->fecha_hora,
        ]);

        return response()->json([
            'message' => 'Recordatorio creado correctamente',
            'data'    => $recordatorio
        ], 201);
    }

    // 🔥 ELIMINAR RECORDATORIO
    public function destroy($id)
    {
        $recordatorio = Recordatorio::find($id);

        if (!$recordatorio) {
            return response()->json([
                'message' => 'Recordatorio no encontrado'
            ], 404);
        }

        $recordatorio->delete();

        return response()->json([
            'message' => 'Recordatorio eliminado correctamente'
        ], 200);
    }
            // 🔄 ACTIVAR / DESACTIVAR
        public function toggle($id)
        {
            $recordatorio = Recordatorio::find($id);

            if (!$recordatorio) {
                return response()->json([
                    'message' => 'Recordatorio no encontrado'
                ], 404);
            }

            $recordatorio->activo = !$recordatorio->activo;
            $recordatorio->save();

            return response()->json([
                'message' => 'Estado actualizado',
                'data' => $recordatorio
            ]);
        }
}