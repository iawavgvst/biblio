<template>
    <div class="book-form-overlay" v-if="isVisible" @click.self="closeForm">
        <SForm class="book-form-container">
            <div class="form-header">
                <h2>{{ isEditMode ? 'Edit Book' : 'Add New Book' }}</h2>
                <button class="close-btn" @click="closeForm">×</button>
            </div>
            <form @submit.prevent="submitForm" class="book-form">
            <SFormRow>
                <label for="title">Book Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="formData.title"
                    :placeholder="isEditMode ? 'Edit book title' : 'Enter book title'"
                    ref="titleInput"
                />
            </SFormRow>
            <SFormRow>
                <label for="author">Author</label>
                <input
                    type="text"
                    id="author"
                    v-model="formData.author"
                    :placeholder="isEditMode ? 'Edit author name' : 'Enter author name'"
                />
            </SFormRow>
            <SFormRow>
                <label for="description">Description</label>
                <SInput type="textarea"
                    id="description"
                    v-model="formData.description"
                    :placeholder="isEditMode ? 'Edit book description' : 'Enter book description'"
                ></SInput>
            </SFormRow>
            <SFormRow>
                <label for="genre">Genre</label>
                <SSelect
                    id="genre"
                    v-model="formData.genre"
                    :options="genreOptions"
                    placeholder="Select genre"
                />
            </SFormRow>
            <SFormRow>
                <label for="cover">Cover of Book</label>
                <input
                    type="text"
                    id="cover"
                    v-model="formData.cover"
                    :placeholder="isEditMode ? 'Edit image URL' : 'Enter image URL for book cover'"
                />
            </SFormRow>
            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" v-model="formData.is18Plus" class="checkbox"/>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-text">There's an age warning (18+)</span>
                </label>
            </div>
            <div class="form-actions">
              <button type="button" class="btn btn-cancel" @click="cancel">Cancel</button>
              <button type="submit" class="btn btn-submit">
                  {{ isEditMode ? 'Save Changes' : 'Add Book' }}
              </button>
            </div>
          </form>
        </SForm>
    </div>
</template>

<script setup>
import { ref, watch, defineEmits, defineProps, onMounted, computed, useTemplateRef } from 'vue';
import { debounce } from 'lodash-es';
import { SForm, SFormRow, SInput, SSelect } from 'startup-ui';

const props = defineProps({
    isVisible: {
        type: Boolean,
        default: false
    },
    bookToEdit: {
        type: Object,
        default: null
    },
    mode: {
        type: String,
        default: 'create',
        validator: (value) => ['create', 'edit'].includes(value)
    }
});

const emit = defineEmits(['save-book', 'close', 'cancel']);

const isEditMode = computed(() => props.mode === 'edit');

const formData = ref({
    id: null,
    title: '',
    author: '',
    description: '',
    genre: '',
    cover: '',
    is18Plus: false
});

const genreOptions = {
    'Detective': 'Detective',
    'Sci-fi': 'Sci-fi',
    'Historical fiction': 'Historical fiction',
    'Dystopia': 'Dystopia',
    'Fantasy': 'Fantasy',
    'Romance novel': 'Romance novel',
    'Short stories': 'Short stories',
    'Horror': 'Horror',
    'Classic': 'Classic',
    'Fairy tale': 'Fairy tale',
    'Memoir': 'Memoir',
    'Biography и Autobiography': 'Biography и Autobiography',
    'Essay': 'Essay',
    'Travel writing': 'Travel writing',
    'Science writing': 'Science writing',
    'How-to books': 'How-to books',
    'True crime': 'True crime',
    'Drama': 'Drama',
    'Romantic novel': 'Romantic novel',
    'Science fiction': 'Science fiction'
};

const titleInput = useTemplateRef('titleInput');

onMounted(() => {
    titleInput.value?.focus();
});

watch(() => props.bookToEdit, (newBook) => {
    if (newBook && isEditMode.value) {
        formData.value = {
            id: newBook.id,
            title: newBook.title || '',
            author: newBook.author || '',
            description: newBook.description || '',
            genre: newBook.genre || '',
            cover: newBook.cover || '',
            is18Plus: newBook.is18Plus || false
        };
    }
}, { immediate: true });

watch(() => props.isVisible, (newVal) => {
    if (!newVal) {
        resetForm();
    } else if (isEditMode.value && props.bookToEdit) {
        formData.value = {
            id: props.bookToEdit.id,
            title: props.bookToEdit.title || '',
            author: props.bookToEdit.author || '',
            description: props.bookToEdit.description || '',
            genre: props.bookToEdit.genre || '',
            cover: props.bookToEdit.cover || '',
            is18Plus: props.bookToEdit.is18Plus || false
        };
    } else if (!isEditMode.value) {
        resetForm();
    }
});

const submitForm = () => {
    if (isEditMode.value) {
        const updatedBook = {
            id: formData.value.id,
            title: formData.value.title,
            author: formData.value.author,
            description: formData.value.description,
            genre: formData.value.genre,
            cover: formData.value.cover,
            is18Plus: formData.value.is18Plus,
            ranking: props.bookToEdit?.ranking || 0
          };
      emit('save-book', updatedBook);
    } else {
        const newBook = {
            id: Date.now(),
            title: formData.value.title,
            author: formData.value.author,
            description: formData.value.description,
            genre: formData.value.genre,
            cover: formData.value.cover,
            is18Plus: formData.value.is18Plus,
            ranking: 0
          };
        emit('save-book', newBook);
    }
    emit('close');
};

const resetForm = () => {
    formData.value = {
        id: null,
        title: '',
        author: '',
        description: '',
        genre: '',
        cover: '',
        is18Plus: false
    };
};

const cancel = () => {
    resetForm();
    emit('close');
};

const closeForm = () => {
    emit('close');
};

const debouncedSend = debounce((newTitle) => {
    if (newTitle && newTitle.trim().length > 0) {
        console.log(`Send to "${newTitle}" Server`);
    }
}, 500);

watch(
    () => formData.value.title,
    (newTitle) => {
        debouncedSend(newTitle);
    }
);
</script>

<style scoped>
.book-form-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

.book-form-container {
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    animation: slideIn 0.3s ease;
}

.form-header {
    position: relative;
    justify-content: center;
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 12px 12px 0 0;
}

h2 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
}

.close-btn {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: #666;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.close-btn:hover {
    background-color: #e0e0e0;
    color: #333;
}

.book-form {
    padding: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.checkbox-group {
    margin-top: 25px;
}

.checkbox {
    position: relative;
    display: inline-block;
    margin: auto;
}

.checkbox-custom {
    border-radius: 8px;
    position: relative;
    align-items: center;
    vertical-align: center;
    display: inline-block;
    padding: 10px 1px;
}

.checkbox-text {
    font-size: 15px;
    color: #333;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
    padding: 10px 10px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.btn {
    padding: 10px 24px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 100px;
    background-color: #f8f9fa;
    border: 1px solid #005bb5;
    color: #005bb5;
}

.btn-cancel {
    background-color: #f8f9fa;
    color: #666;
    border: 1px solid grey;
}

.btn-cancel:hover {
    background-color: #e9ecef;
}

.btn-submit:hover {
    background-color: #9ab5ec;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 91, 181, 0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 600px) {
    .book-form-container {
        width: 95%;
        margin: 10px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>
