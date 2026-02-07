<template>
    <App>
        <div class="book-form-page">
            <div class="header-container">
                <h1>Create New Book</h1>
                <p class="subtitle">
                    Add a new book to your library
                </p>
            </div>
            <div class="form-container">
                <form @submit.prevent="submitForm" class="book-form">
                    <SFormRow class="form-group">
                        <label for="title">Book Title *</label>
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            :class="{ 'error': errors.title }"
                            placeholder="Enter book title"
                        />
                        <div v-if="errors.title" class="error-message">
                            {{ errors.title }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="author">Author *</label>
                        <input
                            type="text"
                            id="author"
                            v-model="form.author"
                            :class="{ 'error': errors.author }"
                            placeholder="Enter author name"
                        />
                        <div v-if="errors.author" class="error-message">
                            {{ errors.author }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="genre">Genre *</label>
                        <select
                            id="genre"
                            v-model="form.genre"
                            :class="{ 'error': errors.genre }"
                        >
                            <option value="">Select genre</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science Fiction">Science Fiction</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Romance">Romance</option>
                            <option value="Biography">Biography</option>
                            <option value="History">History</option>
                            <option value="Self-Help">Self-Help</option>
                            <option value="Other">Other</option>
                        </select>
                        <div v-if="errors.genre" class="error-message">
                            {{ errors.genre }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="description">Description</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            :class="{ 'error': errors.description }"
                            placeholder="Enter book description"
                            rows="4"
                        ></textarea>
                        <div v-if="errors.description" class="error-message">
                            {{ errors.description }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="cover">Cover Image URL</label>
                        <input
                            type="url"
                            id="cover"
                            v-model="form.cover"
                            :class="{ 'error': errors.cover }"
                            placeholder="https://example.com/book-cover.jpg"
                        />
                        <div v-if="errors.cover" class="error-message">
                            {{ errors.cover }}
                        </div>
                        <p class="help-text">
                            Optional. Provide a direct link to the book cover image.
                        </p>
                    </SFormRow>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input
                                type="checkbox"
                                v-model="form.is18Plus"
                            />
                            <span class="checkbox-text">18+ Content</span>
                        </label>
                        <p class="help-text">
                            Check if this book contains adult content.
                        </p>
                    </div>
                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="processing"
                        >
                            <span v-if="processing">Creating...</span>
                            <span v-else>Create Book</span>
                        </button>
                        <Link href="/profile" class="btn btn-secondary">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </App>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import App from '../../layouts/App.vue';
import { SFormRow } from "startup-ui";

const form = useForm({
    title: '',
    author: '',
    genre: '',
    description: '',
    cover: '',
    is18Plus: false,
});

const processing = ref(false);
const errors = computed(() => form.errors);

const submitForm = () => {
    processing.value = true;

    form.post('/books', {
        preserveScroll: true,
        onSuccess: () => {
            processing.value = false;
        },
        onError: (errors) => {
            processing.value = false;
            console.error('Form errors:', errors);
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>

<style scoped>
.book-form-page {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
}

.header-container {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border: 1px solid #dee2e6;
}

h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #333;
}

.subtitle {
    font-size: 16px;
    color: #666;
    font-style: italic;
}

.form-container {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.book-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-group input[type="text"],
.form-group input[type="url"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
}

.form-group input[type="text"]:focus,
.form-group input[type="url"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #005bb5;
    box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.2);
}

.form-group input.error,
.form-group select.error,
.form-group textarea.error {
    border-color: #dc3545;
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.help-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    font-style: italic;
}

.checkbox-group {
    margin-top: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-text {
    font-size: 16px;
    color: #333;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e0e0e0;
}

.btn {
    padding: 12px 28px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 120px;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: linear-gradient(135deg, #005bb5 0%, #003d82 100%);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #004a9c 0%, #002f67 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 91, 181, 0.3);
}

.btn-secondary {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #ddd;
}

.btn-secondary:hover:not(:disabled) {
    background: #e9ecef;
    color: #333;
}

@media (max-width: 768px) {
    .header-container {
        padding: 20px;
    }

    h1 {
        font-size: 28px;
    }

    .form-container {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
