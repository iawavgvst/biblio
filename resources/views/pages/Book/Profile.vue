<template>
    <App>
        <div class="profile-page">
            <div class="header-container">
                <h1>My Profile</h1>
                <p class="subtitle">
                    Manage your personal information and books
                </p>
            </div>
            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-container">
                            <div class="avatar">
                                {{ userInitials }}
                            </div>
                        </div>
                        <div class="profile-info">
                            <h2>{{ auth?.user.name || 'User Name' }}</h2>
                            <p class="email">{{ auth?.user.email || 'user@example.com' }}</p>
                            <p class="stats">
                                <span class="stat-item">
                                    <strong>{{ books.length }}</strong> Books
                                </span>
                                <span class="stat-separator">•</span>
                                <span class="stat-item">
                                    <strong>{{ totalRatings }}</strong> Total Ratings
                                </span>
                            </p>
                        </div>
                    </div>
                    <form @submit.prevent="submitForm" class="profile-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input
                                type="text"
                                id="name"
                                v-model="form.name"
                                :class="{ 'error': errors.name }"
                                placeholder="Enter your full name"
                            />
                            <div v-if="errors.name" class="error-message">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.email"
                                :class="{ 'error': errors.email }"
                                placeholder="Enter your email address"
                            />
                            <div v-if="errors.email" class="error-message">
                                {{ errors.email }}
                            </div>
                        </div>
                        <div class="form-actions">
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Saving...</span>
                                <span v-else>Save Changes</span>
                            </button>
                            <Link href="/books/create" class="btn btn-secondary">
                                Add New Book
                            </Link>
                        </div>
                    </form>
                </div>
                <div class="books-section">
                    <div class="section-header">
                        <h2>My Books</h2>
                        <p class="section-subtitle">
                            Books you have added to the library
                        </p>
                    </div>
                    <div v-if="books.length === 0" class="empty-state">
                        <div class="empty-icon">📚</div>
                        <h3>No books yet</h3>
                        <p>Start by adding your first book to the library</p>
                        <Link href="/books/create" class="btn btn-primary">
                            Add Your First Book
                        </Link>
                    </div>
                    <div v-else class="books-grid">
                        <div
                            v-for="book in books"
                            :key="book.id"
                            class="book-item"
                        >
                            <div class="book-cover-container">
                                <img
                                    :src="book.cover || '/api/placeholder/200/300'"
                                    :alt="book.title"
                                    class="book-cover"
                                />
                                <div class="book-rating-badge">
                                    <span class="rating-star"><font-awesome-icon icon="star"/></span>
                                    <span class="rating-value">{{ book.rating.toFixed(1) }}</span>
                                </div>
                            </div>
                            <div class="book-info">
                                <h3 class="book-title">{{ book.title }}</h3>
                                <p class="book-author">by {{ book.author }}</p>
                                <p class="book-genre">{{ book.genre }}</p>

                                <div class="book-meta">
                                    <span class="meta-item">
                                        <span class="meta-icon">📅</span>
                                        {{ book.created_at }}
                                    </span>
                                    <span class="meta-item">
                                        <span class="meta-icon"><font-awesome-icon icon="star"/></span>
                                        {{ book.ratings_count }} ratings
                                    </span>
                                </div>
                                <div class="book-actions">
                                    <Link
                                        :href="`/books/${book.id}/edit`"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteBook(book.id)"
                                        class="action-btn delete-btn"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navigation-links">
                    <Link href="/public" class="nav-link">
                        <span>Home Page</span>
                    </Link>
                    <Link href="/about" class="nav-link">
                        <span>About Us</span>
                    </Link>
                    <Link href="/scroll-page" class="nav-link">
                        <span>Scroll Page</span>
                    </Link>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup>
import { ref, defineProps, computed, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import App from '../../layouts/App.vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    books: {
        type: Array,
        default: () => []
    },
    user: {
        type: Object,
        default: () => ({})
    },
    auth: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    name: props.auth?.user.name || '',
    email: props.auth?.user.email || '',
});

const processing = ref(false);
const errors = computed(() => form.errors);

const userInitials = computed(() => {
    if (!form.name) return 'JD';
    const names = form.name.split(' ');
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return form.name.substring(0, 2).toUpperCase();
});

const totalRatings = computed(() => {
    return props.books.reduce((sum, book) => sum + book.rating_count, 0);
});

const submitForm = () => {
    alert('You have changed your email address. Please log in.');
    form.put('/profile', {
        preserveScroll: true,
    });
};

const deleteBook = (bookId) => {
    if (confirm(`Delete this book?`)) {
        router.delete(`/books/${bookId}`, {
            preserveScroll: true,
            onSuccess: () => {
            },
            onError: (errors) => {
                console.error('Try again:', errors);
            }
        });
    }
};

onMounted(() => {
    console.log('Profile loaded with books:', props.books.length);
});
</script>

<style scoped>
.profile-page {
    padding: 20px;
    max-width: 1200px;
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
    font-size: 48px;
    margin-bottom: 10px;
    text-transform: uppercase;
    color: #333;
}

.subtitle {
    font-size: 18px;
    color: #666;
    font-style: italic;
}

.profile-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e0e0e0;
}

.avatar-container {
    flex-shrink: 0;
}

.avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #005bb5 0%, #003d82 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0, 91, 181, 0.3);
}

.profile-info h2 {
    margin: 0 0 8px 0;
    font-size: 32px;
    color: #333;
}

.email {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 16px;
}

.stats {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0;
    color: #666;
    font-size: 14px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.stat-item strong {
    color: #005bb5;
    font-size: 18px;
}

.stat-separator {
    color: #ddd;
}

.profile-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-group input[type="text"],
.form-group input[type="email"] {
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
.form-group input[type="email"]:focus {
    outline: none;
    border-color: #005bb5;
    box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.2);
}

.form-group input.error,
.form-group input.error:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    justify-content: space-between;
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

/* Books Section */
.books-section {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.section-header {
    margin-bottom: 30px;
    text-align: center;
}

.section-header h2 {
    font-size: 32px;
    color: #333;
    margin-bottom: 10px;
}

.section-subtitle {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    margin-bottom: 30px;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.book-item {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    background: white;
}

.book-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.book-cover-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.book-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-rating-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 215, 0, 0.9);
    color: #333;
    padding: 6px 12px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: bold;
    font-size: 14px;
    backdrop-filter: blur(5px);
}

.rating-star {
    color: #ffd700;
    font-size: 16px;
}

.book-info {
    padding: 20px;
}

.book-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.book-author {
    color: #666;
    font-size: 14px;
    margin: 0 0 10px 0;
}

.book-genre {
    display: inline-block;
    background: #f0f7ff;
    color: #005bb5;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 15px;
}

.book-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    font-size: 12px;
    color: #888;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.meta-icon {
    font-size: 14px;
}

.book-actions {
    display: flex;
    gap: 10px;
}

.action-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    text-decoration: none;
    text-align: center;
}

.edit-btn {
    background: #f0f7ff;
    color: #005bb5;
    border: 1px solid #005bb5;
}

.edit-btn:hover {
    background: #005bb5;
    color: white;
}

.delete-btn {
    background: #fff5f5;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.delete-btn:hover {
    background: #dc3545;
    color: white;
}

/* Navigation Links */
.navigation-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
}

.nav-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: white;
    color: #333;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    text-align: center;
}

.nav-link:hover {
    background: #f8f9fa;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #005bb5;
}

/* Responsive Design */
@media (max-width: 768px) {
    h1 {
        font-size: 36px;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }

    .books-grid {
        grid-template-columns: 1fr;
    }

    .navigation-links {
        grid-template-columns: 1fr;
    }

    .book-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .profile-card,
    .books-section {
        padding: 20px;
    }

    .avatar {
        width: 80px;
        height: 80px;
        font-size: 28px;
    }

    .profile-info h2 {
        font-size: 24px;
    }

    .section-header h2 {
        font-size: 24px;
    }
}
</style>
