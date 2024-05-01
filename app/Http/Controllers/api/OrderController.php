<?php

namespace App\Http\Controllers\api;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'shipping_address' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            // Add more validation rules as needed
        ]);

        // Create the order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'total_amount' => $request->total_amount,
            'shipping_address' => $request->shipping_address,
            // Add more data for order creation as needed
        ]);

        // Attach order items
        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                // Add more data for order items creation as needed
            ]);
        }

        return response()->json($order, 201);
    }


    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
{
    // Validate the request data
    $request->validate([
        'customer_id' => 'sometimes|required|exists:users,id',
        'total_amount' => 'sometimes|required|numeric|min:0',
        'shipping_address' => 'sometimes|required|string',
        // Add more validation rules as needed
    ]);

    // Update the order
    $order->update([
        'customer_id' => $request->has('customer_id') ? $request->customer_id : $order->customer_id,
        'total_amount' => $request->has('total_amount') ? $request->total_amount : $order->total_amount,
        'shipping_address' => $request->has('shipping_address') ? $request->shipping_address : $order->shipping_address,
        // Add more data for order update as needed
    ]);

    return response()->json($order, 200);
}


    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
