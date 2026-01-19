<template>
    <div id="app">
        <NavBar />
        <main>
            <router-view />
        </main>
        <BasketSlideout />
    </div>
</template>

<script setup>
import NavBar from './components/NavBar.vue';
import BasketSlideout from './components/BasketSlideout.vue';
</script>

<style scoped>
#app {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}
</style>
