import Home from './pages/Home.vue';
import ProductDetail from './pages/ProductDetail.vue';

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
];
