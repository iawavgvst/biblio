<template>
    <App>
        <div class="dashboard-page">
            <div class="header-container">
                <h1>Dashboard</h1>
                <p class="subtitle">
                    Welcome to your personal dashboard
                </p>
            </div>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <h3>Total Books</h3>
                        <p class="stat-value">{{ books.length }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-content">
                        <h3>Rated Books</h3>
                        <p class="stat-value">{{ ratedBooksCount }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔞</div>
                    <div class="stat-content">
                        <h3>18+ Books</h3>
                        <p class="stat-value">{{ adultBooksCount }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <h3>Avg Rating</h3>
                        <p class="stat-value">{{ averageRating.toFixed(1) }}</p>
                    </div>
                </div>
            </div>
            <div class="recent-books">
                <h2>Recent Books</h2>
                <div class="books-list">
                    <div v-for="book in recentBooks" :key="book.id" class="book-item">
                        <img :src="book.cover" :alt="book.title" class="book-thumbnail" />
                        <div class="book-info">
                            <h4>{{ book.title }}</h4>
                            <p class="book-author">{{ book.author }}</p>
                            <div class="book-rating">
                                <span class="rating-stars">
                                    <font-awesome-icon
                                        v-for="star in 5"
                                        :key="star"
                                        :icon="star <= book.ranking ? ['fas', 'star'] : ['far', 'star']"
                                        class="star-icon"
                                    />
                                </span>
                                <span class="rating-value">{{ book.ranking.toFixed(1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navigation-links">
                <Link href="/" class="nav-link">
                    <span>Home Page</span>
                </Link>
                <Link href="/about" class="nav-link">
                    <span>About Us</span>
                </Link>
                <Link href="/profile" class="nav-link">
                    <span>Profile</span>
                </Link>
                <Link href="/scroll-page" class="nav-link">
                    <span>Scroll Page</span>
                </Link>
            </div>
        </div>
    </App>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import App from '../layouts/App.vue';

const props = defineProps({
    books: {
        type: Array,
        default: () => []
    }
});

const ratedBooksCount = computed(() => {
    return props.books.filter(book => book.ranking > 0).length
});

const adultBooksCount = computed(() => {
    return props.books.filter(book => book.is18Plus).length
});

const averageRating = computed(() => {
    const ratedBooks = props.books.filter(book => book.ranking > 0)
    if (ratedBooks.length === 0) return 0
    const total = ratedBooks.reduce((sum, book) => sum + book.ranking, 0)
    return total / ratedBooks.length
});

const recentBooks = computed(() => {
    return [...props.books].slice(0, 3)
});
</script>

<style scoped>
.dashboard-page {
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

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    font-size: 40px;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #005bb5 0%, #003d82 100%);
    color: white;
    border-radius: 50%;
}

.stat-content h3 {
    margin: 0 0 8px 0;
    font-size: 16px;
    color: #666;
    font-weight: 500;
}

.stat-value {
    margin: 0;
    font-size: 32px;
    font-weight: bold;
    color: #333;
}

.recent-books {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.recent-books h2 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.books-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.book-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: background-color 0.3s ease;
}

.book-item:hover {
    background-color: #e9ecef;
}

.book-thumbnail {
    width: 80px;
    height: 120px;
    border-radius: 6px;
    object-fit: cover;
}

.book-info {
    flex: 1;
}

.book-info h4 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #333;
}

.book-author {
    margin: 0 0 12px 0;
    color: #666;
    font-size: 14px;
}

.book-rating {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rating-stars {
    display: flex;
    gap: 4px;
}

.star-icon {
    font-size: 14px;
    color: gold;
}

.rating-value {
    font-weight: bold;
    color: #333;
    font-size: 14px;
}

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

.nav-link span:last-child {
    font-size: 14px;
    font-weight: 500;
}

.quick-actions h2 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.action-btn span:last-child {
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 768px) {
    h1 {
        font-size: 36px;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .books-list {
        grid-template-columns: 1fr;
    }

    .navigation-links {
        grid-template-columns: 1fr;
    }
}
</style>
