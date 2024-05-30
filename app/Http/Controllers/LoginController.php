<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $this->validate($request, [
            'Email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('Email', $data['Email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['status' => 'failure'], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
