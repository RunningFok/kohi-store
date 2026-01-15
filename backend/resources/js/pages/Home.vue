<template>
    <div class="bg-gray-50">
        <!-- Banner Section -->
        <section class="relative w-full">
            <!-- Desktop Banner -->
            <img
                src="/images/banner_web.jpg"
                alt="Banner"
                class="hidden md:block w-full h-[600px] object-cover"
            />
            <!-- Mobile Banner -->
            <img
                src="/images/banner_mobile.jpg"
                alt="Banner"
                class="block md:hidden w-full h-[500px] object-cover"
            />
        </section>

        <div class="container mx-auto px-4 py-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Welcome to KohiStore</h1>
        </div>
    </div>
</template>

<script setup>
// Home page component
</script>
