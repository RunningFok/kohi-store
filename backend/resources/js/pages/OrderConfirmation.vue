<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-3xl mx-auto">
            <div v-if="loading" class="text-center py-12">
                <p class="text-gray-600">Loading order details...</p>
            </div>

            <div v-else-if="error" class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-red-600 mb-4">{{ error }}</p>
                <router-link to="/" class="text-blue-600 hover:underline">Return to Home</router-link>
            </div>

            <div v-else-if="order" class="space-y-6">
                <!-- Success Message -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
                    <p class="text-gray-600">Thank you for your purchase. Your order has been placed successfully.</p>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Details</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number:</span>
                            <span class="font-medium text-gray-900">#{{ order.id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium text-gray-900">{{ formatDate(order.created_at) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium text-green-600 capitalize">{{ order.status }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-xl text-gray-900">${{ parseFloat(order.total_amount).toFixed(2) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Shipping Address</h3>
                        <div class="text-gray-700 space-y-1">
                            <p>{{ order.shipping_address }}</p>
                            <p>{{ order.shipping_city }}, {{ order.shipping_postal_code }}</p>
                            <p>{{ order.shipping_country }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        <div
                            v-for="item in order.order_items"
                            :key="item.id"
                            class="flex items-start justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0"
                        >
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ item.product?.name || 'Product' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">Quantity: {{ item.quantity }}</p>
                                <p class="text-sm text-gray-600">Price: ${{ parseFloat(item.price).toFixed(2) }} each</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ parseFloat(item.subtotal).toFixed(2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Total:</span>
                            <span class="text-2xl font-bold text-gray-900">${{ parseFloat(order.total_amount).toFixed(2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <router-link
                        to="/"
                        class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center"
                    >
                        Continue Shopping
                    </router-link>
                    <router-link
                        to="/account"
                        class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center"
                    >
                        View Orders
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const order = ref(null);
const loading = ref(true);
const error = ref(null);

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

onMounted(async () => {
    try {
        const orderId = route.params.orderId;
        const response = await api.getOrder(orderId);
        order.value = response.data.order;
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load order details';
    } finally {
        loading.value = false;
    }
});
</script>
