<?php

namespace App\Http\Controllers\api;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the product images.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productImages = ProductImage::all();
        return response()->json($productImages);
    }

    /**
     * Store a newly created product image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url' => 'required|url',
            // Add more validation rules as needed
        ]);

        $productImage = ProductImage::create([
            'product_id' => $request->product_id,
            'image_url' => $request->image_url,
            // Add more data for product image creation as needed
        ]);

        return response()->json($productImage, 201);
    }

    /**
     * Display the specified product image.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductImage $productImage)
    {
        return response()->json($productImage);
    }
    public function update(Request $request, ProductImage $productImage)
    {
        $request->validate([
            'image_url' => 'url',
            // Add more validation rules as needed
        ]);

        $productImage->update([
            'image_url' => $request->image_url ?? $productImage->image_url,
            // Add more data for product image update as needed
        ]);

        return response()->json($productImage, 200);
    }

    /**
     * Remove the specified product image from storage.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();
        return response()->json(null, 204);
    }
}
