<template>
    <div class="book-card">
        <div class="book-header">
            <div class="book-cover-container">
                <img
                    :src="book.cover"
                    :alt="book.title"
                    class="book-cover"
                />
                <div v-if="book.is18Plus" class="age-badge">18+</div>
            </div>
            <div class="book-info">
                <h3 class="book-title">{{ book.title }}</h3>
                <p class="book-author">by {{ book.author }}</p>
                <p class="book-genre">{{ book.genre }}</p>
                <div class="book-rating">
                    <div class="rating-stars">
                       <span
                           v-for="star in 5"
                           :key="star"
                           class="star"
                           :class="{ 'filled': star <= Math.round(book.average_rating) }"
                       >
                           <font-awesome-icon icon="star"/>
                       </span>
                    </div>
                    <div class="rating-info">
                        <span class="rating-value">{{ book.average_rating.toFixed(1) }}</span>
                        <span class="rating-count">({{ book.ratings_count }} ratings)</span>
                    </div>
                </div>
                <div v-if="book.description" class="book-description">
                    {{ truncateDescription(book.description) }}
                </div>
            </div>
        </div>
        <div class="book-actions">
            <div v-if="user && !book.has_rated" class="user-rating-section">
                <p class="rating-label">Your rating:</p>
                <div class="rating-input">
                   <span
                       v-for="rating in 5"
                       :key="rating"
                       class="rating-option"
                       :class="[rating <= currentUserRating ? 'selected' : '']"
                       @click="setRating(rating)"
                       @mouseenter="hoverRating = rating"
                       @mouseleave="hoverRating = null"
                   >
                       <font-awesome-icon icon="star"/>
                   </span>
                </div>
                <div v-if="currentUserRating > 0" class="rating-confirm">
                    <button @click="submitRating" class="btn btn-submit">
                        Submit {{ currentUserRating }}/5
                    </button>
                    <button @click="cancelRating" class="btn btn-cancel">
                        Cancel
                    </button>
                </div>
            </div>
            <div v-else-if="user && book.has_rated" class="rated-message">
                <div class="rated-info">
                    <span class="rated-text">Your rating:</span>
                    <div class="user-rating-stars">
                       <span
                           v-for="star in 5"
                           :key="star"
                           class="star"
                           :class="{ 'filled': star <= book.user_rating }"
                       >
                           <font-awesome-icon icon="star"/>
                       </span>
                    </div>
                    <span class="rated-value">{{ book.user_rating }}/5</span>
                </div>
            </div>
            <div v-else class="login-prompt">
                <p class="login-text">Login to rate this book</p>
                <Link href="/login" class="btn btn-login">
                    Login
                </Link>
            </div>
            <div class="action-buttons">
                <Link
                    v-if="canEditBook"
                    :href="`/books/${book.id}/edit`"
                    class="btn btn-edit"
                >
                    Edit
                </Link>
                <button
                    v-if="canDeleteBook"
                    @click="deleteBook"
                    class="btn btn-delete"
                >
                    Delete
                </button>
            </div>
            <div class="book-meta">
               <span class="meta-item">
                   <span class="meta-icon">📅</span>
                   {{ formatDate(book.created_at) }}
               </span>
                <span v-if="book.user" class="meta-item">
                   <span class="meta-icon">👤</span>
                   {{ book.user.name }}
               </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed, ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    book: {
        type: Object,
        required: true
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

const hoverRating = ref(null);
const currentUserRating = ref(props.book.user_rating || 0);

const page = usePage();

const canEditBook = computed(() => {
    return page.props.auth.user && page.props.auth.user.id === props.book.user_id;
});

const canDeleteBook = computed(() => {
    return page.props.auth.user && page.props.auth.user.id === props.book.user_id;
});

const truncateDescription = (description) => {
    if (description && description.length > 350) {
        return description.substring(0, 350) + '...';
    }
    return description;
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const setRating = (rating) => {
    currentUserRating.value = rating;
};

const submitRating = () => {
    if (currentUserRating.value === 0) return;

    router.post(`/books/${props.book.id}/rate`, {
        rating: currentUserRating.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            alert('You have set a rating!');
            router.push('home');
        }
    });
};

const cancelRating = () => {
    currentUserRating.value = props.book.user_rating || 0;
};

function deleteBook() {
    if (!confirm(`Delete "${props.book.title}"?`)) return;

    router.delete(`/books/${props.book.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            router.push('home');
        },
        onError: (errors) => {
            alert('Delete failed: ' + Object.values(errors).join(', '));
        }
    });
}
</script>

<style scoped>
.book-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
    transition: transform 0.3s, box-shadow 0.3s;
    width: 350px;
    max-width: 100%;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.book-header {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.book-cover-container {
    position: relative;
    flex-shrink: 0;
    width: 120px;
    height: 180px;
}

.book-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.age-badge {
    position: absolute;
    top: 10px;
    right: 90px;
    background: #dc3545;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.book-info {
    flex: 1;
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

.book-rating {
    margin-bottom: 15px;
}

.rating-stars {
    display: flex;
    gap: 2px;
    margin-bottom: 5px;
}

.star {
    font-size: 20px;
    color: #ddd;
    transition: color 0.2s;
}

.star.filled {
    color: #ffd700;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.rating-value {
    font-weight: 600;
    color: #333;
}

.rating-count {
    color: #666;
}

.book-description {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}

.book-actions {
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

.debug-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 12px;
    color: #666;
}

.debug-info p {
    margin: 5px 0;
}

.user-rating-section {
    margin-bottom: 20px;
}

.rating-label {
    font-size: 14px;
    color: #666;
    margin: 0 0 10px 0;
    font-weight: 500;
}

.rating-input {
    display: flex;
    gap: 5px;
    margin-bottom: 15px;
}

.rating-option {
    font-size: 28px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
}

.rating-option:hover {
    color: #ffd700;
    transform: scale(1.2);
}

.rating-option.selected {
    color: #ffd700;
}

.rating-confirm {
    display: flex;
    gap: 10px;
}

.rated-message {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.rated-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rated-text {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.user-rating-stars {
    display: flex;
    gap: 2px;
}

.rated-value {
    font-size: 14px;
    color: #333;
    font-weight: 600;
}

.login-prompt {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f0f7ff;
    border-radius: 8px;
    border: 1px solid #cfe2ff;
}

.login-text {
    font-size: 14px;
    color: #005bb5;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.btn {
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

.btn-submit {
    background: #28a745;
    color: white;
}

.btn-submit:hover {
    background: #218838;
}

.btn-cancel {
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-change {
    background: #ffc107;
    color: #212529;
}

.btn-change:hover {
    background: #e0a800;
}

.btn-login {
    background: #005bb5;
    color: white;
}

.btn-login:hover {
    background: #004a94;
}

.btn-edit {
    background: #f0f7ff;
    color: #005bb5;
    border: 1px solid #005bb5;
}

.btn-edit:hover {
    background: #005bb5;
    color: white;
}

.btn-delete {
    background: #fff5f5;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

.book-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
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

@media (max-width: 768px) {
    .book-header {
        flex-direction: column;
    }

    .book-cover-container {
        width: 100%;
        height: 200px;
    }

    .action-buttons,
    .rating-confirm {
        flex-direction: column;
    }

    .rated-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
