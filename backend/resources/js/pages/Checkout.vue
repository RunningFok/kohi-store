<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            <div v-if="loading" class="text-center py-12">
                <p class="text-gray-600">Loading...</p>
            </div>

            <div v-else-if="basketItems.length === 0" class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600 mb-4">Your basket is empty</p>
                <router-link to="/" class="text-blue-600 hover:underline">Continue Shopping</router-link>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Address Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h2>
                        
                        <div v-if="hasAddress && !editingAddress" class="space-y-2">
                            <p class="text-gray-700">{{ customer.address }}</p>
                            <p class="text-gray-700">{{ customer.city }}, {{ customer.postal_code }}</p>
                            <p class="text-gray-700">{{ customer.country }}</p>
                            <p v-if="customer.phone" class="text-gray-700">{{ customer.phone }}</p>
                            <button
                                @click="editingAddress = true"
                                class="mt-4 text-blue-600 hover:underline text-sm"
                            >
                                Edit Address
                            </button>
                        </div>

                        <form v-else @submit.prevent class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Address <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="address"
                                    v-model="addressForm.address"
                                    required
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Street address"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="city"
                                        v-model="addressForm.city"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="City"
                                    />
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                        Postal Code <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="postal_code"
                                        v-model="addressForm.postal_code"
                                        type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Postal Code"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="country"
                                    v-model="addressForm.country"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Country"
                                />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone
                                </label>
                                <input
                                    id="phone"
                                    v-model="addressForm.phone"
                                    type="tel"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Phone number"
                                />
                            </div>

                            <button
                                v-if="hasAddress && editingAddress"
                                @click="editingAddress = false"
                                type="button"
                                class="text-blue-600 hover:underline text-sm"
                            >
                                Cancel
                            </button>
                        </form>
                    </div>

                    <!-- Payment Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Information</h2>
                        <p class="text-sm text-gray-600 mb-4">Using dummy payment details for testing</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Cardholder Name
                                </label>
                                <input
                                    type="text"
                                    value="Tester"
                                    disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Card Number
                                </label>
                                <input
                                    type="text"
                                    value="4242 4242 4242 4242"
                                    disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Expiry Date
                                    </label>
                                    <input
                                        type="text"
                                        value="12/25"
                                        disabled
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        CVV
                                    </label>
                                    <input
                                        type="text"
                                        value="123"
                                        disabled
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed"
                                    />
                                </div>
                            </div>

                            <div v-if="checkoutError" class="text-red-500 text-sm">
                                {{ checkoutError }}
                            </div>

                            <button
                                @click="handleCheckout"
                                :disabled="processing"
                                class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 disabled:bg-gray-400 transition-colors font-medium"
                            >
                                <span v-if="processing">Processing...</span>
                                <span v-else>Complete Order</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <div class="space-y-3 mb-4">
                            <div
                                v-for="item in basketItems"
                                :key="item.product_id"
                                class="flex justify-between text-sm"
                            >
                                <span class="text-gray-600">
                                    {{ item.product_name }} x{{ item.quantity }}
                                </span>
                                <span class="text-gray-900 font-medium">
                                    ${{ (item.price * item.quantity).toFixed(2) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-semibold text-gray-700">Total:</span>
                                <span class="text-2xl font-bold text-gray-900">${{ totalPrice.toFixed(2) }}</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ totalQuantity }} {{ totalQuantity === 1 ? 'item' : 'items' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useBasket } from '../composables/useBasket';
import { useAuth } from '../composables/useAuth';
import api from '../services/api';

const router = useRouter();
const { basketItems, totalQuantity, totalPrice, loadBasket, clearBasket } = useBasket();
const { customer, loadCustomer } = useAuth();

const loading = ref(true);
const processing = ref(false);
const editingAddress = ref(false);
const checkoutError = ref(null);

const addressForm = ref({
    address: '',
    city: '',
    postal_code: '',
    country: '',
    phone: '',
});

const hasAddress = computed(() => {
    return customer.value && customer.value.address && customer.value.city && customer.value.postal_code && customer.value.country;
});

const handleCheckout = async () => {
    processing.value = true;
    checkoutError.value = null;

    try {
        const addressData = hasAddress.value && !editingAddress.value
            ? {}
            : {
                address: addressForm.value.address,
                city: addressForm.value.city,
                postal_code: addressForm.value.postal_code,
                country: addressForm.value.country,
                phone: addressForm.value.phone || customer.value?.phone,
            };

        const response = await api.processCheckout({...addressData});

        // Clear the basket in the frontend to update navbar
        await clearBasket();

        router.push({
            name: 'order-confirmation',
            params: { orderId: response.data.order.id },
        });
    } catch (error) {
        checkoutError.value = error.response?.data?.message || 'Checkout failed. Please try again.';
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            if (errors.address) {
                checkoutError.value = errors.address[0];
            }
        }
    } finally {
        processing.value = false;
    }
};

onMounted(async () => {
    await Promise.all([loadBasket(), loadCustomer()]);
    
    if (customer.value) {
        addressForm.value = {
            address: customer.value.address || '',
            city: customer.value.city || '',
            postal_code: customer.value.postal_code || '',
            country: customer.value.country || '',
            phone: customer.value.phone || '',
        };
    }
    
    loading.value = false;
});
</script>
