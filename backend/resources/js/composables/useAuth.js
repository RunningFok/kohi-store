import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import api, { setAuthToken } from '../services/api';

const customer = ref(null);
const loading = ref(false);
let initialized = false;

export const useAuth = () => {
    const router = useRouter();

    const isAuthenticated = computed(() => customer.value !== null);

    const loadCustomer = async () => {
        try {
            loading.value = true;
            const response = await api.getCurrentCustomer();
            customer.value = response.data.customer;
        } catch (error) {
            if (error.response?.status === 401) {
                setAuthToken(null);
                customer.value = null;
            }
        } finally {
            loading.value = false;
        }
    };

    const login = async (token, customerData) => {
        setAuthToken(token);
        customer.value = customerData;
    };

    const logout = async () => {
        try {
            await api.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            setAuthToken(null);
            customer.value = null;
            router.push('/login');
        }
    };

    if (!initialized) {
        initialized = true;
        const savedToken = localStorage.getItem('auth_token');
        if (savedToken && !customer.value) {
            loadCustomer();
        }
    }

    return {
        customer,
        isAuthenticated,
        loading,
        loadCustomer,
        login,
        logout,
    };
};
