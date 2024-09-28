<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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
            $usuario = User::find($idUser)->first();
            return response()->json($usuario, 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }
}
