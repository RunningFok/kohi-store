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
            <!-- Desktop: Grid Layout (4 items) -->
            <div class="hidden md:grid md:grid-cols-4 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
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
                </div>
            </div>

            <!-- Mobile: Carousel -->
            <div class="md:hidden relative">
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
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                            </div>
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
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Mock product data
const products = ref([
    {
        id: 1,
        name: 'Product 1',
        price: 29.99,
        image: null,
    },
    {
        id: 2,
        name: 'Product 2',
        price: 49.99,
        image: null,
    },
    {
        id: 3,
        name: 'Product 3',
        price: 39.99,
        image: null,
    },
    {
        id: 4,
        name: 'Product 4',
        price: 59.99,
        image: null,
    },
]);

// Carousel state
const currentIndex = ref(0);

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
</script>
