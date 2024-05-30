<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        dd(3);
        $users = User::query()->get();

        return response()->json(['users' => $users], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        User::query()->create($data);

        return response()->json('Success', 200);
    }

    public function show($id)
    {
        $users = User::query()->where('id', $id)->first();

        return response()->json(['users' => $users], 200);

    }

    public function destroy($id)
    {
        user::query()->where('id', $id)->delete();

        return response()->json('Success', 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();
        user::query()->where('id', $id)->update($data);

        return response()->json('Success', 200);
    }
}
