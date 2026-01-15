<template>
    <div class="bg-gray-50">
        <!-- Banner Section -->
        <section class="relative w-full">
            <!-- Desktop Banner -->
            <img
                src="/images/banner_web.jpg"
                alt="Banner"
                class="hidden md:block w-full h-[600px] object-cover"
            />
            <!-- Mobile Banner -->
            <img
                src="/images/banner_mobile.jpg"
                alt="Banner"
                class="block md:hidden w-full h-[500px] object-cover"
            />
        </section>

        <!-- Products Section -->
        <div class="container mx-auto px-4 py-4">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <p class="text-gray-500">Loading Products...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <p class="text-red-500">{{ error }}</p>
            </div>

            <!-- Desktop: Grid Layout (4 items) -->
            <div v-if="products.length > 0" class="hidden md:grid md:grid-cols-4 gap-6">
                <router-link
                    v-for="product in products"
                    :key="product.id"
                    :to="`/products/${product.id}`"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                >
                    <div class="h-48 bg-gray-200">
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
                    <div class="px-4 py-2 flex flex-row justify-between text-base">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                        <p class="font-bold text-gray-900">€{{ product.price.toFixed(2) }}</p>
                    </div>
                </router-link>
            </div>

            <!-- Mobile: Carousel -->
            <div v-if="products.length > 0" class="md:hidden relative">
                <div class="relative overflow-hidden">
                    <div
                        class="flex transition-transform duration-300 ease-in-out"
                        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
                    >
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="min-w-full px-4"
                        >
                            <router-link
                                :to="`/products/${product.id}`"
                                class="block bg-white rounded-lg shadow-md overflow-hidden"
                            >
                                <div class="h-48 bg-gray-200 relative">
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
                                <div class="px-4 py-2 flex flex-row justify-between text-base">
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                                    <p class="font-bold text-gray-900">€{{ product.price.toFixed(2) }}</p>
                                </div>
                            </router-link>
                        </div>
                    </div>

                    <!-- Previous Button (Left Side) -->
                    <button
                        @click="previousProduct"
                        :disabled="currentIndex === 0"
                        class="absolute left-0 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white shadow-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed z-10"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Button (Right Side) -->
                    <button
                        @click="nextProduct"
                        :disabled="currentIndex === products.length - 1"
                        class="absolute right-0 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white shadow-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed z-10"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!loading && !error && products.length === 0" class="text-center py-12">
                <p class="text-gray-500">No Products Available</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const products = ref([]);
const loading = ref(true);
const error = ref(null);

// Carousel state
const currentIndex = ref(0);

// Fetch products from Laravel API
const fetchProducts = async () => {
    try {
        loading.value = true;
        error.value = null;
        
        const response = await api.getProducts();
        products.value = response.data;
    } catch (err) {
        error.value = 'Failed to load products';
        console.error('Error fetching products:', err);
    } finally {
        loading.value = false;
    }
};

const nextProduct = () => {
    if (currentIndex.value < products.value.length - 1) {
        currentIndex.value++;
    }
};

const previousProduct = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

// Fetch products on component mount
onMounted(() => {
    fetchProducts();
});
</script>
