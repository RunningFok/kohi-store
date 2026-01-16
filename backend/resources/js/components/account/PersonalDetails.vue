<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Personal Details</h1>

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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Phone
                    </label>
                    <p class="text-gray-900">{{ customer.phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address
                    </label>
                    <p class="text-gray-900">{{ customer.address }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        City
                    </label>
                    <p class="text-gray-900">{{ customer.city }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Postal Code
                    </label>
                    <p class="text-gray-900">{{ customer.postal_code }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Country
                    </label>
                    <p class="text-gray-900">{{ customer.country }}</p>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-8">
            <p class="text-gray-600">Unable to load customer details.</p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuth } from '../../composables/useAuth';

const { customer, loading } = useAuth();
</script>
