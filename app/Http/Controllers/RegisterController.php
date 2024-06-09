<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'First_name' => 'required|string|max:255',
            'Last_name' => 'required|string|max:255',
            'Email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'Address' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10|max:15',
            'gender' => 'required|string|in:male,female,other',
            'is_admin' => 'sometimes|boolean',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the validated data
        $fields = $validator->validated();

        // Create a new User instance and populate it with the validated data
        $user = new User();
        $user->First_name = $fields['First_name'];
        $user->Last_name = $fields['Last_name'];
        $user->password = Hash::make($fields['password']);
        $user->Email = $fields['Email'];
        $user->Address = $fields['Address'];
        $user->phone_number = $fields['phone_number'];
        $user->gender = $fields['gender'];
        $user->is_admin = $fields['is_admin'] ?? false;
        $user->brand = $request->brand;
        $user->save();

        // Return a success response
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 201);
    }
}
