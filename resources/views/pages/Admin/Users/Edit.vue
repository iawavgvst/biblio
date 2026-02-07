<template>
    <App>
        <div class="edit-user-page">
            <div class="page-header">
                <h1>Edit User</h1>
                <p class="subtitle">
                    Update user information and permissions
                </p>
            </div>
            <div class="edit-container">
                <div class="back-navigation">
                    <Link href="/admin/users" class="back-link">
                        ← Back to Users List
                    </Link>
                </div>
                <form @submit.prevent="submitForm" class="edit-form">
                    <div class="form-section">
                        <h2>User Information</h2>
                        <SFormRow class="form-group">
                            <label for="name">Name</label>
                            <SInput
                                type="text"
                                id="name"
                                v-model="form.name"
                                :class="{ 'error': errors.name }"
                                placeholder="Enter user name"
                            />
                            <div v-if="errors.name" class="error-message">
                                {{ errors.name }}
                            </div>
                        </SFormRow>
                        <SFormRow class="form-group">
                            <label for="email">Email Address</label>
                            <SInput
                                type="email"
                                id="email"
                                v-model="form.email"
                                :class="{ 'error': errors.email }"
                                placeholder="Enter email address"
                            />
                            <div v-if="errors.email" class="error-message">
                                {{ errors.email }}
                            </div>
                        </SFormRow>
                        <SFormRow class="form-group">
                            <label for="role">User Role</label>
                            <select
                                id="role"
                                v-model="form.role"
                                :class="{ 'error': errors.role }"
                                :disabled="isCurrentUser"
                            >
                                <option value="user">Regular User</option>
                                <option value="admin">Administrator</option>
                            </select>
                            <div v-if="errors.role" class="error-message">
                                {{ errors.role }}
                            </div>
                            <div v-if="isCurrentUser" class="info-message">
                                You cannot change your own role
                            </div>
                        </SFormRow>
                    </div>
                    <div class="user-info-section">
                        <h2>User Details</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">User ID:</span>
                                <span class="info-value">{{ user.id }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Current Role:</span>
                                <span :class="['role-badge', user.role]">
                                    {{ user.role }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <SButton
                            type="button"
                            @click="cancelEdit"
                            class="btn-cancel"
                        >
                            Cancel
                        </SButton>
                        <SButton
                            @click="submitForm"
                            :loading="isSubmitting"
                            variant="primary"
                            class="btn-submit"
                        >
                            Save Changes
                        </SButton>
                    </div>
                </form>
            </div>
        </div>
    </App>
</template>

<script setup>
import App from '../../../layouts/App.vue';
import { ref, defineProps, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { SInput, SButton, SFormRow } from 'startup-ui';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    auth: {
        type: Object,
        default: () => ({})
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

const isSubmitting = ref(false);

const isCurrentUser = computed(() => {
    return props.auth.user && props.user.id === props.auth.user.id;
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role
});

const submitForm = () => {
    isSubmitting.value = true;

    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isSubmitting.value = false;
        },
        onError: () => {
            isSubmitting.value = false;
        }
    });
};

const cancelEdit = () => {
    router.visit('/admin/users');
};
</script>

<style scoped>
.edit-user-page {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
}

.page-header {
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

.edit-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.back-navigation {
    margin-bottom: 30px;
}

.back-link {
    color: #005bb5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.back-link:hover {
    color: #003d82;
    text-decoration: underline;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-section h2 {
    font-size: 24px;
    color: #333;
    margin: 0 0 10px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #005bb5;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
}

.form-group input,
.form-group select {
    padding: 12px 16px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background-color: white;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #005bb5;
    box-shadow: 0 0 0 3px rgba(0, 91, 181, 0.1);
}

.form-group input.error,
.form-group select.error {
    border-color: #dc3545;
}

.form-group input.error:focus,
.form-group select.error:focus {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 4px;
}

.info-message {
    color: #6c757d;
    font-size: 14px;
    margin-top: 8px;
    font-style: italic;
}

.user-info-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.user-info-section h2 {
    font-size: 20px;
    color: #333;
    margin: 0 0 15px 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 16px;
    color: #333;
    font-weight: 500;
}

.role-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    display: inline-block;
}

.role-badge.admin {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.role-badge.user {
    background-color: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn-cancel {
    padding: 12px 24px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-cancel:hover {
    background-color: #5a6268;
}

.btn-submit {
    padding: 12px 24px;
    background-color: #005bb5;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
    min-width: 140px;
}

.btn-submit:hover:not(:disabled) {
    background-color: #003d82;
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.danger-zone {
    margin-top: 40px;
    padding: 30px;
    background: #fff5f5;
    border: 2px solid #f8d7da;
    border-radius: 12px;
}

.danger-zone h2 {
    font-size: 24px;
    color: #dc3545;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f8d7da;
}

.warning-text h3 {
    font-size: 18px;
    color: #dc3545;
    margin: 0 0 8px 0;
}

.warning-text p {
    color: #721c24;
    margin: 0;
    line-height: 1.5;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    color: #333;
}

.modal-body p {
    margin: 0 0 10px 0;
    color: #495057;
    line-height: 1.5;
}

.btn-cancel {
    padding: 10px 20px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-cancel:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    h1 {
        font-size: 36px;
    }

    .edit-container {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-submit {
        width: 100%;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
