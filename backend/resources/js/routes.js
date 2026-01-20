import Home from './pages/Home.vue';
import ProductDetail from './pages/ProductDetail.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import CustomerAccount from './pages/CustomerAccount.vue';
import Checkout from './pages/Checkout.vue';
import OrderConfirmation from './pages/OrderConfirmation.vue';

export default [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            title: 'kohiSTORE - Premium Coffee Shop | Buy Coffee Online',
            description: 'Shop premium coffee products at kohiSTORE. Browse our collection of high-quality coffee beans and accessories. Fast shipping and secure checkout.',
        },
    },
    {
        path: '/products/:id',
        name: 'product-detail',
        component: ProductDetail,
        meta: {
            title: 'Product Details | kohiSTORE',
            description: 'View product details, pricing, and availability. Add to cart and checkout securely.',
        },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'Login | kohiSTORE',
            description: 'Sign in to your kohiSTORE account to access your cart, order history, and exclusive offers.',
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            title: 'Create Account | kohiSTORE',
            description: 'Create a new kohiSTORE account to start shopping. Get access to order tracking, saved addresses, and more.',
        },
    },
    {
        path: '/account',
        name: 'account',
        component: CustomerAccount,
        meta: {
            title: 'My Account | kohiSTORE',
            description: 'Manage your account details, view order history, and update your personal information.',
        },
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: Checkout,
        meta: {
            title: 'Checkout | kohiSTORE',
            description: 'Complete your order with secure checkout. Review your items and shipping information.',
        },
    },
    {
        path: '/checkout/confirmation/:orderId',
        name: 'order-confirmation',
        component: OrderConfirmation,
        meta: {
            title: 'Order Confirmation | kohiSTORE',
            description: 'Your order has been confirmed. View order details and tracking information.',
        },
    },
];
