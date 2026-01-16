<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request): JsonResponse
    {
        $customer = $request->user('sanctum');
        $cacheKey = "basket:customer:{$customer->id}";
        $basket = Cache::get($cacheKey, []);

        if (empty($basket)) {
            return response()->json([
                'message' => 'Your basket is empty',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $needsAddress = empty($customer->address) && empty($customer->city) && empty($customer->country);
        
        if ($needsAddress && (!$request->address || !$request->city || !$request->country)) {
            return response()->json([
                'message' => 'Address is required',
                'errors' => [
                    'address' => ['Please provide a complete shipping address'],
                ]
            ], 422);
        }

        if ($request->address || $request->city || $request->postal_code || $request->country || $request->phone) {
            $customer->address = $request->address ?? $customer->address;
            $customer->city = $request->city ?? $customer->city;
            $customer->postal_code = $request->postal_code ?? $customer->postal_code;
            $customer->country = $request->country ?? $customer->country;
            $customer->phone = $request->phone ?? $customer->phone;
            $customer->save();
        }

        $shippingAddress = $request->address ?? $customer->address;
        $shippingCity = $request->city ?? $customer->city;
        $shippingPostalCode = $request->postal_code ?? $customer->postal_code;
        $shippingCountry = $request->country ?? $customer->country;

        if (empty($shippingAddress) || empty($shippingCity) || empty($shippingCountry)) {
            return response()->json([
                'message' => 'Complete shipping address is required',
                'errors' => [
                    'address' => ['Please provide a complete shipping address with all required fields'],
                ]
            ], 422);
        }

        try {
            DB::beginTransaction();

            $productIds = array_column($basket, 'product_id');
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');
            
            foreach ($basket as $item) {
                $product = $products->get($item['product_id']);
                if (!$product) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Product not found',
                        'errors' => ['product_id' => ['Product with ID ' . $item['product_id'] . ' not found']]
                    ], 404);
                }
                
                if ($product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Insufficient stock',
                        'errors' => [
                            'quantity' => ['Insufficient stock for ' . $product->name . '. Available: ' . $product->stock_quantity . ', Requested: ' . $item['quantity']]
                        ]
                    ], 422);
                }
            }

            $totalAmount = 0;
            foreach ($basket as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'status' => 'completed',
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress,
                'shipping_city' => $shippingCity,
                'shipping_postal_code' => $shippingPostalCode,
                'shipping_country' => $shippingCountry,
            ]);

            foreach ($basket as $item) {
                $product = $products->get($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            Cache::forget($cacheKey);

            DB::commit();

            $order->load('orderItems.product');

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
