<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers() {
        try {
            $user = User::with('rol')->get();
            return response()->json(['data' => $user, 'size' => count($user)], 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }

    public function perfil($idUser) {
        try {
            $usuario = User::find($idUser);
            return response()->json($usuario, 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }

    public function addAvatar(Request $request, $idUsuario) {
        try {
            //request body: avatar
            DB::beginTransaction();
            $request->validate([
                'avatar' => 'required|file|image|mimes:jpeg,png,jpg,svg|max:2048',
            ], [
                'avatar.required' => 'El campo avatar es obligatorio.',
                'avatar.image' => 'El archivo debe ser una imagen.',
                'avatar.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o svg.',
                'avatar.max' => 'La imagen no debe pesar más de 2 MB.'
            ]);
            $usuario = User::find($idUsuario)->first();
            $imagen = $request->avatar;
            $tiempo = Carbon::now()->format('Y-m-d-H-i-s');
            $nuevoNombre = "{$tiempo}-{$idUsuario}-{$usuario->nickname}.{$imagen->extension()}";
            $imagen->storeAs("/avatar/{$idUsuario}", $nuevoNombre, 'public');

            $usuario->avatar = $nuevoNombre;
            $usuario->save();

            DB::commit();
            return response()->json(['message' => 'Avatar actualizado con exito.'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''.$e], 500);
        }
    }
}
