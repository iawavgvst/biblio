<template>
    <App>
        <div class="profile-page">
            <div class="header-container">
                <h1>Profile</h1>
                <p class="subtitle">
                    Manage your personal information
                </p>
            </div>
            <SForm class="profile-container">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-container">
                            <div class="avatar">
                                {{ userInitials }}
                            </div>
                        </div>
                        <div class="profile-info">
                            <h2>{{ form.name || 'User Name' }}</h2>
                            <p class="email">{{ form.email || 'user@example.com' }}</p>
                        </div>
                    </div>
                    <form @submit.prevent="submitForm" class="profile-form">
                        <SFormRow class="form-group">
                            <label for="name">Full Name</label>
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
                        </SFormRow>
                        <SFormRow class="form-group">
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
                        </SFormRow>
                        <SFormRow class="form-group">
                            <label for="bio">Bio</label>
                            <SInput type="textarea"
                                id="bio"
                                v-model="form.bio"
                                rows="4"
                                placeholder="Tell us something about yourself..."
                            ></SInput>
                        </SFormRow>
                        <div class="form-actions">
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="processing"
                            >
                                <span v-if="processing">Saving...</span>
                                <span v-else>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="navigation-links">
                    <Link href="/" class="nav-link">
                        <span>Home Page</span>
                    </Link>
                    <Link href="/about" class="nav-link">
                        <span>About Us</span>
                    </Link>
                    <Link href="/dashboard" class="nav-link">
                        <span>Dashboard</span>
                    </Link>
                    <Link href="/scroll-page" class="nav-link">
                        <span>Scroll Page</span>
                    </Link>
                </div>
            </SForm>
        </div>
    </App>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import App from '../layouts/App.vue';
import { SForm, SFormRow, SInput } from 'startup-ui';

const form = useForm({
    name: 'John Doe',
    email: 'john.doe@example.com',
    bio: 'Book lover and avid reader. Enjoy science fiction and historical novels.'
});

const processing = ref(false);

const errors = computed(() => form.errors);

const userInitials = computed(() => {
    if (!form.name) return 'JD'
    const names = form.name.split(' ')
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase()
    }
    return form.name.substring(0, 2).toUpperCase()
});

const submitForm = () => {
    processing.value = true
    setTimeout(() => {
        alert('Profile updated successfully!')
        processing.value = false
    }, 1000);
};
</script>

<style scoped>
.profile-page {
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
    margin: 0;
    color: #666;
    font-size: 16px;
}

.profile-form {
    margin-bottom: 40px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-group input[type="text"],
.form-group input[type="email"],
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
.form-group input[type="email"]:focus,
.form-group textarea:focus {
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

.form-group textarea {
    resize: vertical;
    min-height: 100px;
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

    .navigation-links {
        grid-template-columns: 1fr;
    }
}
</style>
