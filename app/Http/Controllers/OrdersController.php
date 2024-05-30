<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    // Method to retrieve all orders with associated products and user
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::with(['products.image' => function ($query) {
            $query->orderBy('updated_at', 'desc'); // You can specify the order of images if needed
        }])->whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->get();

        return response()->json($orders);
    }

    // Method to store a new order
    public function store(Request $request)
    {
        $order = new Order();
        $order->total_price = 0;
        $order->save();

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['pivot']['quantity'];
            $itemPrice = $product->Price * $quantity;
            $order->total_price += $itemPrice;
            $order->products()->attach($product->id, ['quantity' => $quantity, 'item_price' => $itemPrice]);
        }

        // Assign user to the order
        $user = Auth::user()->id;
        $order->user()->associate($user);
        $order->save();

        return response()->json($order, 201);
    }

    // Method to show a specific order
    public function show($id)
    {
        $userId = Auth::id();

        $orders = Order::with(['products.image' => function ($query) {
            $query->orderBy('updated_at', 'desc'); // You can specify the order of images if needed
        }])->whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->where('id', $id)->get();

        return response()->json($orders->first());
    }

    // Method to update an existing order
    public function update(Request $request, $id)
    {
        // Find the order by its ID
        $order = Order::findOrFail($id);

        // Update the total price if needed
        // Assuming the total price is the sum of item prices
        // You may need to adjust this based on your business logic
        $totalPrice = 0;
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['pivot']['quantity'];
            $itemPrice = $product->Price * $quantity;
            $totalPrice += $itemPrice;
        }
        $order->total_price = $totalPrice;

        // Sync products for the order
        $order->products()->detach(); // Remove existing products
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['pivot']['quantity'];
            $itemPrice = $product->Price * $quantity;
            $order->products()->attach($product->id, ['quantity' => $quantity, 'item_price' => $itemPrice]);
        }

        // Save the updated order
        $order->save();

        // Return a response indicating success
        return response()->json($order, 200);
    }

    // Method to delete an existing order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }
}
