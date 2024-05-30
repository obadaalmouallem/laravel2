<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('image')->get();

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'Name' => 'required',
            'Description' => 'required',
            'Price' => 'required',
            'Quantity' => 'required',
            'size' => 'required',
            'category_id' => 'required',
        ]);

        $product = new Product();
        $product->Name = $validation['Name'];
        $product->Description = $validation['Description'];
        $product->Price = $validation['Price'];
        $product->Quantity = $validation['Quantity'];
        $product->size = $validation['size'];
        $product->category_id = $validation['category_id'];
        $product->user_id = Auth::user()->id;
        $product->save();

        return response()->json('Saved Successfully', 200);

    }

    public function show($id)
    {
        $product = Product::with('image')->findOrFail($id);
        $comments = $product->comment;
        $like = $product->like;

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $product = Product::findOrFail($id);
        $product->Name = $request['Name'] ?? $product->Name;
        $product->Description = $request['Description'] ?? $product->Description;
        $product->Price = $request['Price'] ?? $product->Price;
        $product->Quantity = $request['Quantity'] ?? $product->Quantity;
        $product->size = $request['size'] ?? $product->size;
        $product->category_id = $request['category_id'] ?? $product->category_id;
        $product->save();

        return response()->json('Successfully Updated', 200);
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::user()->id)->findOrFail($id);

        $product->delete();

        return response()->json('Successfully Deleted', 201);

    }
}
