<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with('products')->get();

        return response()->json($orders, 200);
    }

    public function show($id)
    {
        $order = Order::with('products')->find($id);

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::create();

        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['id']);

            if (! $product) {
                $order->delete(); // Rollback if product not found

                return response()->json(['error' => 'Product not found'], 404);
            }

            // Attach product to order with quantity
            $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
        }

        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Detach all existing products from the order
        $order->products()->detach();

        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['id']);

            if (! $product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Attach product to order with quantity
            $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
        }

        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
