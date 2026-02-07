<template>
    <App>
        <div class="home-page">
            <div class="btn-container">
                <h1>Books List</h1>
                <SButton class="btn" @click="goToCreate">ADD</SButton>
            </div>
            <div class="book-container">
                <BookCard
                    v-for="book in books"
                    :key="book.id"
                    :book="book"
                    @delete="deleteBook"
                    @rating-change="handleRatingChange"
                />
            </div>
        </div>
    </App>
</template>

<script setup>
import { reactive } from 'vue';
import { SButton } from 'startup-ui';
import { router } from '@inertiajs/vue3';
import BookCard from '../components/BookCard.vue';
import App from '../layouts/App.vue';

const props = defineProps({
    books: Array
});

const books = reactive([...props.books]);

const goToCreate = () => {
    router.visit('/books/create')
};

const handleRatingChange = (bookId, newRating) => {
    const book = books.find(b => b.id === bookId)
    if (book) {
        book.rating = newRating
    }
};
</script>

<style scoped>
.home-page {
    margin: auto;
}

h1 {
    font-size: 60px;
    margin-top: 13px;
    margin-bottom: 13px;
    text-align: center;
    color: black;
}

.btn-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}

.book-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.btn {
    padding: 10px 16px;
    margin: 13px 15px 13px 15px;
    font-size: 0.7rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
    border-radius: 8px;
    background-color: transparent;
    border: 1px solid #005bb5;
    color: #005bb5;
    font-weight: 500;
    min-width: 35px;
}

.btn:hover {
    background-color: #9ab5ec;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 91, 181, 0.3);
}

.btn:active {
    transform: translateY(0);
}

.reset-btn {
    border-color: #dc3545;
    color: #dc3545;
}

.reset-btn:hover {
    background-color: #f8d7da;
}
</style>
