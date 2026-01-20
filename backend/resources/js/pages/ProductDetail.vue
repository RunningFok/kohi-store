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
                                <picture v-if="product.image">
                                    <source :srcset="product.image.replace(/\.(jpg|jpeg|png)$/i, '.webp')" type="image/webp" />
                                    <img
                                        :src="product.image"
                                        :alt="product.name"
                                        class="w-full h-full object-cover"
                                        width="800"
                                        height="800"
                                        loading="eager"
                                        decoding="async"
                                        sizes="(max-width: 768px) 100vw, 50vw"
                                    />
                                </picture>
                                <picture v-else>
                                    <source srcset="/images/product_default.webp" type="image/webp" />
                                    <img
                                        src="/images/product_default.jpg"
                                        alt="No Image"
                                        class="w-full h-full object-cover"
                                        width="800"
                                        height="800"
                                        loading="eager"
                                        decoding="async"
                                        sizes="(max-width: 768px) 100vw, 50vw"
                                    />
                                </picture>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="md:w-1/2 p-4 md:p-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                            
                            <div class="mb-6">
                                <p class="text-xl font-bold text-gray-900 mb-2">€{{ product.price.toFixed(2) }}</p>
                                <p v-if="product.availability === 'available'" class="text-green-600 text-sm">
                                    In Stock
                                </p>
                                <p v-else class="text-red-600 text-sm">
                                    Out of Stock
                                </p>
                            </div>

                            <div v-if="product.description" class="mb-6">
                                <p class="text-gray-600 text-sm md:text-base">{{ product.description }}</p>
                            </div>

                            <!-- Add to Basket Button -->
                            <div class="flex gap-4">
                                <button
                                    @click="addToBasket"
                                    :disabled="product.availability !== 'available'"
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
import { useBasket } from '../composables/useBasket';
import { useAuth } from '../composables/useAuth';
import { useBasketSlideout } from '../composables/useBasketSlideout';

const route = useRoute();
const router = useRouter();
const { addItem } = useBasket();
const { isAuthenticated } = useAuth();
const { openBasket } = useBasketSlideout();

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
    if (!isAuthenticated.value) {
        router.push('/login');
        return;
    }

    try {
        addItem(product.value);
        openBasket();
    } catch (error) {
        alert(error.message);
    }
};

onMounted(() => {
    fetchProduct();
});
</script>
