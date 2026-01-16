import { ref, computed, watch } from 'vue';
import { useAuth } from './useAuth';
import api from '../services/api';

const basketItems = ref([]);
let initialized = false;
let loadingBasket = false;

export const useBasket = () => {
    const { isAuthenticated, customer } = useAuth();

    const loadBasket = async () => {
        if (loadingBasket) return;
        
        loadingBasket = true;
        try {
            if (isAuthenticated.value) {
                const response = await api.getBasket();
                basketItems.value = response.data.basket || [];
            } else {
                basketItems.value = [];
            }
        } catch (error) {
            console.error('Error loading basket:', error);
            basketItems.value = [];
        } finally {
            loadingBasket = false;
        }
    };

    const saveBasket = async () => {
        if (!isAuthenticated.value) {
            return;
        }
        
        try {
            await api.saveBasket(basketItems.value);
        } catch (error) {
            console.error('Error saving basket:', error);
        }
    };

    if (!initialized) {
        initialized = true;
        loadBasket();
    }

    watch(isAuthenticated, async (newValue, oldValue) => {
        if (newValue && !oldValue) {
            await loadBasket();
        } else if (!newValue && oldValue) {
            basketItems.value = [];
        }
    });

    watch(customer, async (newCustomer) => {
        if (newCustomer && isAuthenticated.value && initialized) {
            await loadBasket();
        }
    });

    const totalQuantity = computed(() => {
        return basketItems.value.reduce((total, item) => total + item.quantity, 0);
    });

    const totalPrice = computed(() => {
        return basketItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });

    const addItem = async (product) => {
        if (!isAuthenticated.value) {
            throw new Error('Please login to add items to basket');
        }

        const existingItem = basketItems.value.find(item => item.product_id === product.id);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            basketItems.value.push({
                product_id: product.id,
                product_name: product.name,
                price: product.price,
                quantity: 1,
            });
        }

        await saveBasket();
    };

    return {
        basketItems,
        totalQuantity,
        totalPrice,
        addItem,
        loadBasket,
    };
};
