<template>
    <div class="min-h-full bg-gray-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Register</h1>

                <form @submit.prevent="handleRegister" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="your@email.com"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            minlength="8"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••"
                        />
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone (Optional)
                        </label>
                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="+1234567890"
                        />
                    </div>

                    <div v-if="error" class="text-red-500 text-sm">
                        <div v-if="typeof error === 'string'">{{ error }}</div>
                        <div v-else>
                            <div v-for="(errors, field) in error" :key="field" class="mb-1">
                                <strong>{{ field }}:</strong> {{ errors[0] }}
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 disabled:bg-gray-400 transition-colors font-medium"
                    >
                        <span v-if="loading">Creating account...</span>
                        <span v-else>Register</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <router-link to="/login" class="text-blue-600 hover:underline">
                            Login here
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api, { setAuthToken } from '../services/api';
import { useAuth } from '../composables/useAuth';

const router = useRouter();

const form = ref({
    name: '',
    email: '',
    password: '',
    phone: '',
});

const loading = ref(false);
const error = ref(null);

const handleRegister = async () => {
    try {
        loading.value = true;
        error.value = null;

        const response = await api.register(form.value);
        
        setAuthToken(response.data.token);
        
        const { login } = useAuth();
        await login(response.data.token, response.data.customer);
        
        router.push('/');
    } catch (err) {
        if (err.response?.data?.errors) {
            error.value = err.response.data.errors;
        } else {
            error.value = err.response?.data?.message || 'Registration failed. Please try again.';
        }
    } finally {
        loading.value = false;
    }
};
</script>
