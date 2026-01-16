<template>
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <router-link to="/" class="text-2xl font-bold text-gray-900">
                    KohiStore
                </router-link>

                <div class="flex items-center gap-4">
                    <router-link
                        :to="isAuthenticated ? '/account' : '/login'"
                        class="p-2 text-gray-700 hover:text-gray-900 transition-colors"
                        :title="isAuthenticated ? 'My Account' : 'Login'"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </router-link>

                    <button
                        @click="toggleBasket"
                        class="relative p-2 text-gray-700 hover:text-gray-900 transition-colors"
                        aria-label="Open basket"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span
                            v-if="totalQuantity > 0"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
                        >
                            {{ totalQuantity > 99 ? '99+' : totalQuantity }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth';
import { useBasket } from '../composables/useBasket';
import { useBasketSlideout } from '../composables/useBasketSlideout';

const { customer, isAuthenticated, loadCustomer } = useAuth();
const { totalQuantity } = useBasket();
const { toggleBasket } = useBasketSlideout();

onMounted(() => {
    const token = localStorage.getItem('auth_token');
    if (token && !customer.value) {
        loadCustomer();
    }
});
</script>
