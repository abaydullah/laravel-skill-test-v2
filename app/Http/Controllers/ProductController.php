<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'quantity_in_stock' => 'required|integer|min:0',
                'price_per_item' => 'required|numeric|min:0',
            ]);
            $product = Product::create($validated);

            return response()->json([
                'message' => 'Product added successfully!',
                'product' => $product->append('total_value_number'),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function fetch()
    {
        $products = Product::latest()->get()->append('total_value_number');

        return response()->json($products);
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'quantity_in_stock' => 'required|integer|min:0',
                'price_per_item' => 'required|numeric|min:0',
            ]);

            $product->update($validated);

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product->append('total_value_number'),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
