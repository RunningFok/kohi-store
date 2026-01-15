<template>
    <div id="app">
        <NavBar />
        <main>
            <router-view />
        </main>
    </div>
</template>

<script setup>
import NavBar from './components/NavBar.vue';
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
