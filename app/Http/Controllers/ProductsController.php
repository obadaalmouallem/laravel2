<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('image', 'sizes')->get();

        return response()->json(['products' => $products], 200);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validation = $request->validate([
            'Name' => 'required',
            'Description' => 'required',
            'Price' => 'required|numeric',
            'Quantity' => 'required|integer',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'category_id' => 'required|integer',
        ]);
        // dd(2);

        $product = new Product();
        $product->fill($validation);
        $product->user_id = Auth::id();
        $product->save();

        $product->sizes()->sync($validation['sizes']);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::with('image', 'sizes')->findOrFail($id);

        return response()->json(['product' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->Name = $request->input('Name', $product->Name);
        $product->Description = $request->input('Description', $product->Description);
        $product->Price = $request->input('Price', $product->Price);
        $product->Quantity = $request->input('Quantity', $product->Quantity);
        $product->category_id = $request->input('category_id', $product->category_id);
        $product->save();
        // Check if sizes are provided in the request and update the relationship
        // dd($request->all());
        if ($request->has('size')) {
            $sizes = $request->input('size');
            $product->sizes()->sync($sizes);
        }

        return response()->json('Successfully Updated', 200);
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
