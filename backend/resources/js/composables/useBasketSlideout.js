import { ref } from 'vue';

const isOpen = ref(false);

export const useBasketSlideout = () => {
    const openBasket = () => {
        isOpen.value = true;
        document.body.style.overflow = 'hidden';
    };

    const closeBasket = () => {
        isOpen.value = false;
        document.body.style.overflow = '';
    };

    const toggleBasket = () => {
        if (isOpen.value) {
            closeBasket();
        } else {
            openBasket();
        }
    };

    return {
        isOpen,
        openBasket,
        closeBasket,
        toggleBasket,
    };
};
