<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers() {
        try {
            $user = User::get();
            return response()->json(['data' => $user, 'size' => count($user)], 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }
}
