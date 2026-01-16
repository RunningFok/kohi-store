<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BasketController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $customer = $request->user('sanctum');
        $cacheKey = "basket:customer:{$customer->id}";
        
        $basket = Cache::get($cacheKey, []);
        
        return response()->json([
            'basket' => $basket,
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $request->validate([
            'basket' => 'required|array',
            'basket.*.product_id' => 'required|integer',
            'basket.*.product_name' => 'required|string',
            'basket.*.price' => 'required|numeric',
            'basket.*.quantity' => 'required|integer|min:1',
        ]);

        $customer = $request->user('sanctum');
        $cacheKey = "basket:customer:{$customer->id}";
        
        Cache::put($cacheKey, $request->basket, now()->addDays(30));
        
        return response()->json([
            'message' => 'Basket saved successfully',
            'basket' => $request->basket,
        ]);
    }
}
