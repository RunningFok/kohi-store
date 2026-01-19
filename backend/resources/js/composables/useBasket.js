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
        
        if (basketItems.value.length === 0) {
            return;
        }
        
        try {
            const normalizedBasket = basketItems.value.map(item => ({
                product_id: Number(item.product_id),
                product_name: String(item.product_name || ''),
                price: Number(item.price),
                quantity: Number(item.quantity),
            }));
            
            await api.saveBasket(normalizedBasket);
        } catch (error) {
            console.error('Error saving basket:', error);
            if (error.response?.data) {
                console.error('Response data:', error.response.data);
                if (error.response.data.errors) {
                    console.error('Validation errors:', error.response.data.errors);
                }
            }
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
            existingItem.quantity = Number(existingItem.quantity) + 1;
        } else {
            basketItems.value.push({
                product_id: Number(product.id),
                product_name: String(product.name || ''),
                price: Number(product.price),
                quantity: 1,
            });
        }

        await saveBasket();
    };

    const removeItem = async (productId) => {
        const idToRemove = Number(productId);
        const beforeCount = basketItems.value.length;
        
        basketItems.value = basketItems.value.filter(item => Number(item.product_id) !== idToRemove);
        
        if (basketItems.value.length < beforeCount) {
            if (basketItems.value.length === 0) {
                try {
                    if (isAuthenticated.value) {
                        await api.clearBasket();
                    }
                } catch (error) {
                    console.error('Error clearing basket:', error);
                }
            } else {
                await saveBasket();
            }
        }
    };

    const updateQuantity = async (productId, quantity) => {
        const item = basketItems.value.find(item => item.product_id === productId);
        if (item) {
            const qty = Number(quantity);
            if (qty <= 0) {
                await removeItem(productId);
            } else {
                item.quantity = qty;
                await saveBasket();
            }
        }
    };

    const clearBasket = async () => {
        basketItems.value = [];
        try {
            if (isAuthenticated.value) {
                await api.clearBasket();
            }
        } catch (error) {
            console.error('Error clearing basket:', error);
        }
    };

    return {
        basketItems,
        totalQuantity,
        totalPrice,
        addItem,
        removeItem,
        updateQuantity,
        clearBasket,
        loadBasket,
    };
};
