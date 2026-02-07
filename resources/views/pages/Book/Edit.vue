<template>
    <App>
        <div class="book-form-page">
            <div class="header-container">
                <h1>Edit Book</h1>
                <p class="subtitle">
                    Update book information
                </p>
            </div>
            <div class="form-container">
                <form @submit.prevent="submitForm" class="book-form">
                    <div class="form-group">
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
                    </div>
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
                    <div class="book-stats" v-if="book">
                        <h3>Book Statistics</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Average Rating:</span>
                                <span class="stat-value">{{ book.average_rating ? Number(book.average_rating).toFixed(1) : 'N/A' }}<font-awesome-icon
                                    icon="star"/></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Ratings:</span>
                                <span class="stat-value">{{ book.ratings_count || 0 }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Created:</span>
                                <span class="stat-value">{{ formatDate(book.created_at) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="processing"
                        >
                            <span v-if="processing">Updating...</span>
                            <span v-else>Update Book</span>
                        </button>
                        <Link href="/profile" class="btn btn-outline">
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
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    book: {
        type: Object,
        required: true,
        default: () => ({})
    }
});

const form = useForm({
    title: props.book.title || '',
    author: props.book.author || '',
    genre: props.book.genre || '',
    description: props.book.description || '',
    cover: props.book.cover || '',
    is18Plus: props.book.is18Plus || false,
});

const processing = ref(false);
const errors = computed(() => form.errors);

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const submitForm = () => {
    processing.value = true;

    form.put(`/books/${props.book.id}`, {
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

.book-stats {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.book-stats h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #333;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.stat-value {
    font-size: 16px;
    font-weight: 600;
    color: #005bb5;
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

.btn-outline {
    background: transparent;
    color: #666;
    border: 1px solid #ddd;
}

.btn-outline:hover:not(:disabled) {
    background: #f8f9fa;
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

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
