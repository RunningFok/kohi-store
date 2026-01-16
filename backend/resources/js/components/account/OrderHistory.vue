<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Order History</h1>

        <div v-if="loading" class="text-center py-8">
            <p class="text-gray-600">Loading orders...</p>
        </div>

        <div v-else-if="error" class="text-center py-8">
            <p class="text-red-600">{{ error }}</p>
        </div>

        <div v-else-if="orders.length === 0" class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <p class="text-gray-600 mb-2">You have no orders yet.</p>
            <router-link to="/" class="text-blue-600 hover:underline inline-block">Start Shopping</router-link>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="order in orders"
                :key="order.id"
                class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
            >
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Order #{{ order.id }}
                            </h3>
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-sm font-medium',
                                    getStatusClass(order.status)
                                ]"
                            >
                                {{ order.status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            {{ formatDate(order.created_at) }}
                        </p>
                        <p v-if="order.order_items" class="text-sm text-gray-600 mt-1">
                            {{ order.order_items.length }} {{ order.order_items.length === 1 ? 'item' : 'items' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-gray-900">
                            ${{ parseFloat(order.total_amount).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div v-if="order.order_items && order.order_items.length > 0" class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Items:</h4>
                    <div class="space-y-2">
                        <div
                            v-for="item in order.order_items"
                            :key="item.id"
                            class="flex items-center justify-between text-sm"
                        >
                            <span class="text-gray-700">
                                {{ item.product?.name || 'Product' }} × {{ item.quantity }}
                            </span>
                            <span class="text-gray-900 font-medium">
                                ${{ parseFloat(item.subtotal).toFixed(2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div v-if="order.shipping_address" class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Shipping Address:</h4>
                    <p class="text-sm text-gray-600">{{ order.shipping_address }}</p>
                    <p class="text-sm text-gray-600">{{ order.shipping_city }}, {{ order.shipping_postal_code }}</p>
                    <p class="text-sm text-gray-600">{{ order.shipping_country }}</p>
                </div>

                <!-- View Details Link -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <router-link
                        :to="{ name: 'order-confirmation', params: { orderId: order.id } }"
                        class="text-blue-600 hover:underline text-sm font-medium"
                    >
                        View Full Details →
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const orders = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchOrders = async () => {
    try {
        loading.value = true;
        error.value = null;
        const response = await api.getOrders();
        orders.value = response.data.orders || [];
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load orders';
        console.error('Error loading orders:', err);
    } finally {
        loading.value = false;
    }
};

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

const getStatusClass = (status) => {
    const statusClasses = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
};

onMounted(() => {
    fetchOrders();
});
</script>
