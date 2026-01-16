<template>
    <!-- Backdrop -->
    <Transition name="backdrop">
        <div
            v-if="isOpen"
            class="fixed inset-0 z-40"
            @click="closeBasket"
        ></div>
    </Transition>

    <!-- Slideout Panel -->
    <Transition name="slideout">
        <aside
            v-if="isOpen"
            class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex flex-col"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Shopping Basket</h2>
                <button
                    @click="closeBasket"
                    class="p-2 text-gray-500 hover:text-gray-700 transition-colors rounded-full hover:bg-gray-100"
                    aria-label="Close basket"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Basket Items -->
            <div class="flex-1 overflow-y-auto p-6">
                <div v-if="basketItems.length === 0" class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Your basket is empty</p>
                    <p class="text-gray-400 text-sm mt-2">Add some products to get started!</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="item in basketItems"
                        :key="item.product_id"
                        class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow"
                    >
                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">{{ item.product_name }}</h3>
                            <p class="text-gray-600 text-sm mt-1">${{ item.price.toFixed(2) }} each</p>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3 mt-3">
                                <button
                                    @click="decreaseQuantity(item.product_id, item.quantity)"
                                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="text-gray-900 font-medium w-8 text-center">{{ item.quantity }}</span>
                                <button
                                    @click="increaseQuantity(item.product_id, item.quantity)"
                                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Price and Remove -->
                        <div class="flex flex-col items-end gap-2">
                            <p class="text-lg font-bold text-gray-900">
                                ${{ (item.price * item.quantity).toFixed(2) }}
                            </p>
                            <button
                                @click="removeItem(item.product_id)"
                                class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors"
                                aria-label="Remove item"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer with Total and Actions -->
            <div v-if="basketItems.length > 0" class="border-t border-gray-200 p-6 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-lg font-semibold text-gray-700">Total:</span>
                    <span class="text-2xl font-bold text-gray-900">${{ totalPrice.toFixed(2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-600 mb-6">
                    <span>{{ totalQuantity }} {{ totalQuantity === 1 ? 'item' : 'items' }}</span>
                </div>
                <div class="space-y-2">
                    <button
                        @click="handleCheckout"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                    >
                        Proceed to Checkout
                    </button>
                    <button
                        @click="clearBasket"
                        class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors"
                    >
                        Clear Basket
                    </button>
                </div>
            </div>
        </aside>
    </Transition>
</template>

<script setup>
import { useBasket } from '../composables/useBasket';
import { useBasketSlideout } from '../composables/useBasketSlideout';
import { useRouter } from 'vue-router';

const { basketItems, totalQuantity, totalPrice, removeItem, updateQuantity, clearBasket } = useBasket();
const { isOpen, closeBasket } = useBasketSlideout();
const router = useRouter();

const increaseQuantity = async (productId, currentQuantity) => {
    await updateQuantity(productId, currentQuantity + 1);
};

const decreaseQuantity = async (productId, currentQuantity) => {
    await updateQuantity(productId, currentQuantity - 1);
};

const handleCheckout = () => {
    closeBasket();
};
</script>

<style scoped>
/* Slideout transition */
.slideout-enter-active {
    transition: transform 0.3s ease-out;
}

.slideout-leave-active {
    transition: transform 0.3s ease-in;
}

.slideout-enter-from {
    transform: translateX(100%);
}

.slideout-leave-to {
    transform: translateX(100%);
}
</style>
