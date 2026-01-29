<template>
    <App>
        <div class="home-page">
            <div class="btn-container">
                <h1>Books List</h1>
                <SButton class="btn" @click="openBookForm">ADD</SButton>
                <SButton class="btn reset-btn" @click="resetAllRatings">RESET RATINGS</SButton>
            </div>
            <div class="book-container">
                <BookCard
                    v-for="book in books"
                    :key="book.id"
                    :book="book"
                    @update="updateBook"
                    @delete="deleteBook"
                    @rating-change="handleRatingChange"
                />
            </div>
            <Dialog
                v-if="showBookForm"
                @close="closeBookForm">
                <template #title>
                    <h2>{{ isEditMode ? 'Edit Book' : 'Add New Book' }}</h2>
                </template>
                <BookForm
                    :is-visible="showBookForm"
                    :book-to-edit="bookToEdit"
                    :mode="isEditMode ? 'edit' : 'create'"
                    @save-book="handleSaveBook"
                    @close="closeBookForm"
                    @cancel="closeBookForm"
                />
            </Dialog>
        </div>
    </App>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { SButton } from 'startup-ui'
import BookCard from '../components/BookCard.vue'
import BookForm from '../components/BookForm.vue'
import Dialog from '../components/Dialog.vue'
import App from '../layouts/App.vue'

const props = defineProps({
    books: Array
})

const books = reactive([...props.books])

const showBookForm = ref(false)
const isEditMode = ref(false)
const bookToEdit = ref(null)

const openBookForm = () => {
    isEditMode.value = false
    bookToEdit.value = null
    showBookForm.value = true
}

const closeBookForm = () => {
    showBookForm.value = false
    isEditMode.value = false
    bookToEdit.value = null
}

const handleSaveBook = (book) => {
    if (isEditMode.value) {
        updateBook(book)
    } else {
        addNewBook(book)
    }
    closeBookForm()
}

const addNewBook = (newBook) => {
    books.push(newBook);
};

const updateBook = (updatedBook) => {
    const index = books.findIndex(book => book.id === updatedBook.id);
    if (index !== -1) {
        books[index] = updatedBook;
    }
};

const deleteBook = (bookId) => {
    const index = books.findIndex(book => book.id === bookId);
    if (index !== -1) {
        books.splice(index, 1);
    }
};

const resetAllRatings = () => {
    if (confirm('Are you sure you want to reset all ratings to 0?')) {
        books.forEach(book => {
            book.ranking = 0;
        });
        console.log('All ratings have been reset to 0');
    }
};

const handleRatingChange = (bookId, newRating) => {
    const book = books.find(b => b.id === bookId);
    if (book) {
        book.ranking = newRating;
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

h2 {
    font-size: 25px;
    margin-top: 13px;
    margin-bottom: 13px;
    text-align: left;
    text-transform: uppercase;
    color: darkblue;
    font-style: italic;
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
</style>
