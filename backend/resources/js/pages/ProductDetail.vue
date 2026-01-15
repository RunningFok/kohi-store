<template>
    <div class="bg-gray-50">
        <div class="container mx-auto px-4 py-4">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <p class="text-gray-500">Loading product...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <p class="text-red-500">{{ error }}</p>
                <router-link to="/" class="text-blue-600 hover:underline mt-4 inline-block">
                    Back to Home
                </router-link>
            </div>

            <!-- Product Detail -->
            <div v-else-if="product" class="max-w-6xl mx-auto">
                <!-- Back Button -->
                <button
                    @click="$router.back()"
                    class="mb-4 text-gray-600 hover:text-gray-900 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </button>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="md:flex">
                        <!-- Product Image -->
                        <div class="md:w-1/2">
                            <div class="aspect-square bg-gray-200">
                                <img
                                    v-if="product.image"
                                    :src="product.image"
                                    :alt="product.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="md:w-1/2 p-6 md:p-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                            
                            <div class="mb-6">
                                <p class="text-3xl font-bold text-gray-900 mb-2">€{{ product.price.toFixed(2) }}</p>
                                <p v-if="product.product_availability === 'available'" class="text-green-600 text-sm">
                                    In Stock
                                </p>
                                <p v-else-if="product.product_availability === 'out of stock'" class="text-red-600 text-sm">
                                    Out of Stock
                                </p>
                                <p v-else class="text-gray-600 text-sm">
                                    Unavailable
                                </p>
                            </div>

                            <div v-if="product.description" class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                                <p class="text-gray-600">{{ product.description }}</p>
                            </div>

                            <!-- Add to Basket Button -->
                            <div class="flex gap-4">
                                <button
                                    @click="addToBasket"
                                    :disabled="product.product_availability !== 'available'"
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium"
                                >
                                    Add to Basket
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const router = useRouter();

const product = ref(null);
const loading = ref(true);
const error = ref(null);

// Fetch product by ID
const fetchProduct = async () => {
    try {
        loading.value = true;
        error.value = null;

        const productId = route.params.id;
        const response = await api.getProduct(productId);
        product.value = response.data;
    } catch (err) {
        error.value = 'Failed to load product';
        console.error('Error fetching product:', err);
    } finally {
        loading.value = false;
    }
};

const addToBasket = () => {
    // TODO: Implement add to basket logic
    console.log('Add to basket:', product.value);
    // You can add basket logic here or use a store
};

onMounted(() => {
    fetchProduct();
});
</script>
