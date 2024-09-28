<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function registrarUsuario(Request $request) {
        try {
            DB::beginTransaction();
            $email = User::where('email', $request->email)->first();
            if ($email) return response()->json(['error' => 'Este email de usuario ya esta en uso.'], 400);
            $nickname = User::where('email', $request->nickname)->first();
            if ($nickname) return response()->json(['error' => 'Este nickname de usuario ya esta en uso.'], 400);

            $password = bcrypt($request->password);

            $user = User::create([ 
                'rol_id' => 3, 
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => $password,
            ]);

            $token = JWTAuth::fromUser($user);
            DB::commit();
            return response()->json(['message' => 'Usuario creado con exito', 'token' => $token], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''. $e], 500);
        }
    }

    public function login(Request $request) {
        try {
            $credenciales = $request->only('email', 'password');
            $user = User::where('email', $request->email)->first();
            if (!$user) return response()->json(['error' => 'Usuario no encontrado.'], 401);
            if ($user->estado_registro !== 'A') return response()->json(['error' => 'Usuario en modo inactivo'], 401);
            $token = JWTAuth::attempt($credenciales);
            if (!$token) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
            return response()->json(['message' => 'Login exitoso', 'token' => $token], 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }

    public function logout() {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Desconectado correctamente.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => '' . $e], 500);
        }
    }
}
