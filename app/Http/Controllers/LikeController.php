<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $validatedData = $request->validate([
            'product_id' => 'required',
        ]);
        // Create and save the like
        $validatedData['user_id'] = $userId;
        $like = Like::create($validatedData);

        return response()->json($like, 201);
    }

    public function destroy($id)
    {
        $product = Like::findOrFail($id);
        $product->delete();

        return response()->json('Successfully Deleted', 201);

    }
}
