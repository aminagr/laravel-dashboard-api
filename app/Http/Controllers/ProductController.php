<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        try {
            // Log the action of retrieving products
            Log::info('Fetching all products');

            $products = Product::all();
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Log incoming request data
            Log::info('Incoming request to create product: ' . json_encode($request->all()));

            // Validate incoming data
            $validated = $request->validate([
                'productName' => 'required|string|max:255',
                'color' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
            ]);

            // Create the product after validation
            $product = Product::create($validated);

            // Log successful creation
            Log::info('Product created successfully: ' . json_encode($product));

            return response()->json($product, 201);
        } catch (\Exception $e) {
            // Log any errors during product creation
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create product'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Log incoming request data for updating
            Log::info('Incoming request to update product with ID ' . $id . ': ' . json_encode($request->all()));

            // Find the product by ID
            $product = Product::findOrFail($id);

            // Validate incoming data
            $validated = $request->validate([
                'productName' => 'required|string|max:255',
                'color' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
            ]);

            // Update the product
            $product->update($validated);

            // Log successful update
            Log::info('Product updated successfully: ' . json_encode($product));

            return response()->json($product, 200);
        } catch (\Exception $e) {
            // Log any errors during product update
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update product'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Log the action of deleting a product
            Log::info('Incoming request to delete product with ID ' . $id);

            // Find the product by ID
            $product = Product::findOrFail($id);

            // Delete the product
            $product->delete();

            // Log successful deletion
            Log::info('Product deleted successfully with ID ' . $id);

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            // Log any errors during product deletion
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }
}
