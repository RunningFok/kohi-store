import Home from './pages/Home.vue';
import ProductDetail from './pages/ProductDetail.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import CustomerAccount from './pages/CustomerAccount.vue';
import Checkout from './pages/Checkout.vue';

export default [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/products/:id',
        name: 'product-detail',
        component: ProductDetail,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/account',
        name: 'account',
        component: CustomerAccount,
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: Checkout,
    },
];
