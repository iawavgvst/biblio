<template>
    <div class="book-card">
        <div class="card-content">
            <h2>{{ book.title }}</h2>
            <div class="icons">
                <SButton @click="openEditForm">✏️</SButton>
                <SButton @click="handleDelete">🗑️</SButton>
            </div>
            <p><strong>Author: </strong>{{ book.author }}</p>
            <p><strong>Description: </strong>{{ book.description }}</p>
            <p><strong>Genre: </strong>{{ book.genre }}</p>
            <div class="interactive-stars-container">
                <strong>Rating: </strong>
                <div class="stars-wrapper">
                    <div
                        v-for="star in 5"
                        :key="star"
                        class="star"
                        :class="{
                            'active': star <= currentRating,
                            'inactive': star > currentRating,
                            'clickable': !ratingLocked
                        }"
                        @click="setRating(star)"
                    >
                        <font-awesome-icon
                            :icon="star <= currentRating ? ['fas', 'star'] : ['far', 'star']"
                            class="star-icon"
                        />
                    </div>
                </div>
                <span class="rating-value">{{ currentRating.toFixed(1) }}</span>
                <span v-if="ratingLocked" class="rating-locked">✓</span>
            </div>
            <p><strong>There's an age warning (18+): </strong> {{ book.is18Plus }}</p>
            <div class="cover-container">
                <div class="cover-image-wrapper">
                    <img :src="book.cover" alt="Book Cover" class="book-cover" />
                    <div
                        class="cover-star"
                        :class="{ 'has-rating': hasRating }"
                    >
                        <font-awesome-icon
                            :icon="hasRating ? ['fas', 'star'] : ['far', 'star']"
                            class="cover-star-icon"
                        />
                        <span class="cover-rating-value">
                                  {{ hasRating ? currentRating.toFixed(1) : '0.0' }}
                            </span>
                    </div>
                </div>
            </div>
        </div>
        <BookForm
            v-if="showEditForm"
            :is-visible="showEditForm"
            :book-to-edit="book"
            mode="edit"
            @save-book="handleSave"
            @close="closeEditForm"
            @cancel="closeEditForm"
        />
    </div>
</template>

<script setup>
import { ref, defineProps, computed, onMounted, defineEmits, watch } from 'vue';
import { SButton } from 'startup-ui'
import BookForm from './BookForm.vue';

const props = defineProps({
    book: Object,
});

const emit = defineEmits(['update', 'delete', 'rating-change']);

const showEditForm = ref(false);

const openEditForm = () => {
    showEditForm.value = true;
};

const closeEditForm = () => {
    showEditForm.value = false;
};

const handleSave = (updatedBook) => {
    console.log('handleSave', updatedBook);
    emit('update', updatedBook);
    closeEditForm();
};

const handleDelete = () => {
    if (confirm(`Are you sure you want to delete "${props.book.title}"?`)) {
        emit('delete', props.book.id);
    }
};

const currentRating = ref(props.book.ranking || 0);
const ratingLocked = ref(false);

const hasRating = computed(() => {
    return currentRating.value >= 1;
});

const setRating = (rating) => {
    if (!ratingLocked.value) {
        currentRating.value = rating;
        ratingLocked.value = true;
        emit('rating-change', props.book.id, rating);
    }
};

watch(() => props.book.ranking, (newRanking) => {
    currentRating.value = newRanking;
    if (newRanking > 0) {
        ratingLocked.value = true;
    } else {
        ratingLocked.value = false;
    }
});

onMounted(() => {
    if (props.book.ranking > 0) {
        ratingLocked.value = true;
    }
});
</script>

<style scoped>
.book-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 5px;
    margin: 5px 10px;
    width: 255px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
}

.card-content {
    flex: 1;
    margin: 0;
}

h2 {
    font-size: 20px;
    margin-top: 13px;
    margin-bottom: 13px;
}

.icons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 30px;
}

.icons button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

img {
    width: 155px;
    height: 230px;
    border-radius: 8px;
    align-items: flex-end;
    object-fit: cover;
}

.interactive-stars-container {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.stars-wrapper {
    display: flex;
    gap: 10px;
}

.star {
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 13px;
    height: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.star.clickable:hover {
    transform: scale(1.2);
    background-color: rgba(255, 204, 0, 0.1);
}

.star.active {
    color: gold;
    text-shadow: 0 0 5px rgba(255, 204, 0, 0.5);
}

.star.inactive {
    color: darkgrey;
}

.rating-value {
    font-weight: bold;
    color: #333;
    font-size: 15px;
    min-width: 15px;
}

.rating-locked {
    color: darkgreen;
    font-weight: bold;
    font-size: 15px;
}

.cover-container {
    margin: 10px 45px;
}

.cover-image-wrapper {
    position: relative;
    display: inline-block;
    width: 155px;
    height: 230px;
}

.book-cover {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    object-fit: cover;
    display: block;
}

.cover-star {
    position: absolute;
    top: -18px;
    left: -23px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    z-index: 10;
    color: gold;
}

.cover-star:not(.has-rating) {
    background-color: transparent;
    color: #666666;
}

.cover-star.has-rating {
    background-color: transparent;
    border: none;
}

.cover-rating-value {
    position: absolute;
    bottom: -10px;
    right: -10px;
    background: white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    font-size: 15px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #FFCC00;
    color: #333;
    z-index: 11;
}

.cover-star:not(.has-rating) .cover-rating-value {
    border-color: #CCCCCC;
    color: #666666;
}
</style>
