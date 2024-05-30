<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoriesContoller extends Controller
{
    public function index()
    {
        $categories = Categorie::all();

        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {

        $validation = $request->validate([
            'name' => 'required',
        ]);
        $category = new Categorie();
        $category->name = $validation['name'];
        $category->save();

        return response()->json('Saved Successfully', 200);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $category = Categorie::findOrFail($id);
        $category->name = $request['name'] ?? $category->name;
        $category->save();

        return response()->json('Successfully Updated', 200);
    }

    public function show($id)
    {
        $category = Categorie::findOrFail($id);

        return response()->json($category, 200);

    }

    public function destroy($id)
    {
        $category = Categorie::findOrFail($id);
        $category->delete();

        return response()->json('Successfully Deleted', 201);

    }
}
