<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-6">
                <aside class="w-full md:w-64 bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">My Account</h2>
                    <nav class="space-y-2">
                        <button
                            @click="activeSection = 'personal'"
                            :class="[
                                'w-full text-left px-4 py-2 rounded-md transition-colors',
                                activeSection === 'personal'
                                    ? 'bg-blue-600 text-white'
                                    : 'text-gray-700 hover:bg-gray-100'
                            ]"
                        >
                            Personal Details
                        </button>
                        <button
                            @click="handleLogout"
                            class="w-full text-left px-4 py-2 rounded-md text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors"
                        >
                            Log Out
                        </button>
                    </nav>
                </aside>

                <main class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <PersonalDetails v-if="activeSection === 'personal'" />
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import PersonalDetails from '../components/account/PersonalDetails.vue';

const router = useRouter();
const { customer, logout, loadCustomer } = useAuth();
const activeSection = ref('personal');

const handleLogout = async () => {
    await logout();
};

onMounted(async () => {
    if (!customer.value) {
        await loadCustomer();
        if (!customer.value) {
            router.push('/login');
        }
    }
});
</script>
