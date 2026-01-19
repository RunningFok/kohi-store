<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Personal Details</h1>
            <button
                v-if="customer && !isEditing"
                @click="startEditing"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium"
            >
                Edit Address
            </button>
        </div>

        <div v-if="loading" class="text-center py-8">
            <p class="text-gray-600">Loading...</p>
        </div>

        <div v-else-if="customer" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Full Name
                    </label>
                    <p class="text-gray-900">{{ customer.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <p class="text-gray-900">{{ customer.email }}</p>
                </div>
            </div>

            <!-- Editable Address Fields -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h2>
                
                <form v-if="isEditing" @submit.prevent="handleSave" class="space-y-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone
                        </label>
                        <input
                            id="phone"
                            v-model="formData.phone"
                            type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Phone number"
                        />
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <textarea
                            id="address"
                            v-model="formData.address"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Street address"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                City
                            </label>
                            <input
                                id="city"
                                v-model="formData.city"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="City"
                            />
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                Postal Code
                            </label>
                            <input
                                id="postal_code"
                                v-model="formData.postal_code"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Postal Code"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                            Country
                        </label>
                        <input
                            id="country"
                            v-model="formData.country"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Country"
                        />
                    </div>

                    <div v-if="error" class="text-red-500 text-sm">
                        {{ error }}
                    </div>

                    <div v-if="success" class="text-green-500 text-sm">
                        {{ success }}
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 transition-colors font-medium"
                        >
                            <span v-if="saving">Saving...</span>
                            <span v-else>Save Changes</span>
                        </button>
                        <button
                            type="button"
                            @click="cancelEditing"
                            :disabled="saving"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:bg-gray-100 transition-colors font-medium"
                        >
                            Cancel
                        </button>
                    </div>
                </form>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone
                        </label>
                        <p class="text-gray-900">{{ customer.phone || 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Address
                        </label>
                        <p class="text-gray-900">{{ customer.address || 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            City
                        </label>
                        <p class="text-gray-900">{{ customer.city || 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Postal Code
                        </label>
                        <p class="text-gray-900">{{ customer.postal_code || 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Country
                        </label>
                        <p class="text-gray-900">{{ customer.country || 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-8">
            <p class="text-gray-600">Unable to load customer details.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useAuth } from '../../composables/useAuth';
import api from '../../services/api';

const { customer, loading, loadCustomer } = useAuth();
const isEditing = ref(false);
const saving = ref(false);
const error = ref(null);
const success = ref(null);

const formData = ref({
    phone: '',
    address: '',
    city: '',
    postal_code: '',
    country: '',
});

watch(customer, (newCustomer) => {
    if (newCustomer) {
        formData.value = {
            phone: newCustomer.phone || '',
            address: newCustomer.address || '',
            city: newCustomer.city || '',
            postal_code: newCustomer.postal_code || '',
            country: newCustomer.country || '',
        };
    }
}, { immediate: true });

const startEditing = () => {
    isEditing.value = true;
    error.value = null;
    success.value = null;
    if (customer.value) {
        formData.value = {
            phone: customer.value.phone || '',
            address: customer.value.address || '',
            city: customer.value.city || '',
            postal_code: customer.value.postal_code || '',
            country: customer.value.country || '',
        };
    }
};

const cancelEditing = () => {
    isEditing.value = false;
    error.value = null;
    success.value = null;
    if (customer.value) {
        formData.value = {
            phone: customer.value.phone || '',
            address: customer.value.address || '',
            city: customer.value.city || '',
            postal_code: customer.value.postal_code || '',
            country: customer.value.country || '',
        };
    }
};

const handleSave = async () => {
    saving.value = true;
    error.value = null;
    success.value = null;

    try {
        await api.updateAddress(formData.value);
        
        success.value = 'Address updated successfully';
        isEditing.value = false;
        
        await loadCustomer();
        
        setTimeout(() => {
            success.value = null;
        }, 3000);
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to update address';
        if (err.response?.data?.errors) {
            const errors = err.response.data.errors;
            const errorMessages = Object.values(errors).flat();
            error.value = errorMessages.join(', ');
        }
    } finally {
        saving.value = false;
    }
};
</script>
