<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {

        $userId = Auth::user()->id;
        // Validate the request data
        $validatedData = $request->validate([
            'product_id' => 'required',
            'comment' => 'required|string',
        ]);

        // Add the authenticated user's ID to the validated data
        $validatedData['user_id'] = $userId;

        // Create and save the comment
        $comment = Comment::create($validatedData);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrfail($id);
        $comment->comment = $request['comment'] ?? $comment->comment;
        $comment->save();

        return response()->json('Comment Updated Successfully');
    }

    public function destroy($id)
    {
        $product = Comment::findOrFail($id);
        $product->delete();

        return response()->json('Successfully Deleted', 201);

    }
}
