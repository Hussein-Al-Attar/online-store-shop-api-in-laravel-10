<?php

namespace App\Http\Controllers\api;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json([
                'status' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve products:' . $e->getMessage());
            // التعامل مع الأخطاء بشكل أفضل
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve products',], 500);
        }
    }


    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|numeric|min:0',
                'description' => 'required|string',
                'category_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false,'errors' => $validator->errors()], 400);
            }
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'data' => $product,
                'url' => route('products.show', $product->id) // Assuming there's a route to show product details
            ], 201);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Failed to create product: ' . $e->getMessage());

            // Return a generic error message to the user
            return response()->json(['status' => false,'message' => 'Unauthorized'], 500);
        }
    }


    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Product retrieved successfully',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve products:' . $e->getMessage());
            // التعامل مع الأخطاء بشكل أفضل
            return response()->json(['status' => false,'message' => 'Failed to retrieve product'], 500);
        }
    }


    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|numeric|min:0',
                'description' => 'required|string',
                'category_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => true ,'errors' => $validator->errors()], 400);
            }
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            return response()->json($product, 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Failed to update product: ' . $e->getMessage());
            return response()->json(['status' => false ,'message' => 'Failed to update product'], 500);
        }
    }


    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['status' => true ,'message' => 'Product deleted successfully'], 202);
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Failed to delete product' . $e->getMessage());
            return response()->json(['status' => false ,'message' => 'Failed to delete product'], 500);
        }
    }

}
