<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // dd($request->all());
        $data = [];
        $fields = $this->validate($request, [
            'First_name' => 'required|string',
            'Last_name' => 'required|string',
            'Email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'Address' => 'required|string',
        ]);
        $user = new User();
        $user->First_name = $fields['First_name'];
        $user->Last_name = $fields['Last_name'];
        $user->password = Hash::make($fields['password']);
        $user->Email = $fields['Email'];
        $user->Address = $fields['Address'];
        $user->is_admin = $request->is_admin;
        $user->save();

        $data['status'] = 'success';

        return response()->json($data, 200);
    }
}
