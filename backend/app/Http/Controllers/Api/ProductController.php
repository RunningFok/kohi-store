<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::where('status', 'active')
            ->select('id', 'name', 'description', 'price', 'image', 'status')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => (float) $product->price,
                    'image' => null,
                    'product_availability' => $product->availability,
                ];
            });

        return response()->json($products);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::where('status', 'active')
            ->where('id', $id)
            ->select('id', 'name', 'description', 'price', 'image', 'status')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'image' => null,
            'product_availability' => $product->availability,
        ]);
    }
}
