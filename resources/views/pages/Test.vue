<template>
    <App>
        <div class="container">
            <p><strong>Число</strong> - {{ integer }}</p>
            <p><strong>Строка</strong> - {{ string }}</p>
            <p><strong>Фрукты (массив)</strong> - {{ array }}</p>
            <p><strong>Человек (ассоциативный массив)</strong> - {{ associativeArray }}</p>
        </div>
    </App>
</template>

<script setup>
import App from '../layouts/App.vue'
import { defineProps } from 'vue'

defineProps({
    integer: Number,
    string: String,
    array: Array,
    associativeArray: Object,
})
</script>

<style scoped>
.container {
    max-width: 1350px;
    margin: 0 auto;
    padding: 20px 0;
}
</style>
