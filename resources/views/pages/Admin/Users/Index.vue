<template>
    <App>
        <div class="users-page">
            <div class="page-header">
                <h1>User Management</h1>
                <p class="subtitle">
                    Manage all users in the system
                </p>
            </div>
            <div class="users-table-container">
                <div class="table-header">
                    <div class="table-title">
                        <h2>All Users</h2>
                        <span class="user-count">{{ users.length }} users</span>
                    </div>
                    <Link href="/admin/dashboard" class="back-link">
                        ← Back to Dashboard
                    </Link>
                </div>
                <div class="table-wrapper">
                    <table class="users-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Books</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                    <span :class="['role-badge', user.role]">
                                        {{ user.role }}
                                    </span>
                            </td>
                            <td>
                                    <span class="books-count">
                                        {{ user.books_count }}
                                    </span>
                            </td>
                            <td class="actions">
                                <SButton
                                    @click="editUser(user)"
                                    class="btn-edit"
                                >
                                    Edit
                                </SButton>
                                <SButton
                                    @click="deleteUser(user)"
                                    class="btn-delete"
                                >
                                    Delete
                                </SButton>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="users.length === 0" class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h3>No users found</h3>
                    <p>There are no users in the system yet.</p>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup>
import App from '../../../layouts/App.vue';
import { defineProps } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { SButton } from 'startup-ui';

defineProps({
    users: {
        type: Array,
        default: () => []
    },
    auth: {
        type: Object,
        default: () => ({})
    }
});

function editUser(user) {
    router.get(`/admin/users/${user.id}/edit`);
};

function deleteUser(user) {
    if (confirm(`Вы уверены, что хотите удалить ${user.name}?`)) {
        router.delete(`/admin/users/${user.id}`)
    }
};
</script>

<style scoped>
.users-page {
    padding: 20px;
    max-width: 1200px;
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

.users-table-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.table-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.table-title h2 {
    font-size: 28px;
    margin: 0;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.user-count {
    background: #e9ecef;
    color: #495057;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.back-link {
    color: #005bb5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
    padding: 8px 16px;
    border: 1px solid #005bb5;
    border-radius: 6px;
}

.back-link:hover {
    color: white;
    background-color: #005bb5;
}

.table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.users-table th {
    text-align: center;
    padding: 16px;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
}

.users-table td {
    padding: 16px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    text-align: center;
}

.users-table tr:hover {
    background-color: #f8f9fa;
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

.books-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background-color: #e7f1ff;
    color: #005bb5;
    border-radius: 50%;
    font-weight: 600;
    font-size: 14px;
}

.actions {
    display: flex;
    gap: 8px;
    min-width: 150px;
    justify-content: center;
}

.btn-edit {
    padding: 8px 16px;
    background-color: #005bb5;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: inline-block;
    text-align: center;
}

.btn-edit:hover {
    background-color: #003d82;
}

.btn-delete {
    padding: 8px 16px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-delete:hover {
    background-color: #c82333;
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
    color: #495057;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
    font-size: 16px;
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

@media (max-width: 768px) {
    h1 {
        font-size: 36px;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .back-link {
        align-self: flex-start;
    }

    .users-table {
        font-size: 14px;
    }

    .users-table th,
    .users-table td {
        padding: 12px 8px;
    }

    .actions {
        flex-direction: column;
        gap: 6px;
        min-width: 120px;
    }

    .btn-edit,
    .btn-delete {
        width: 100%;
        padding: 6px 12px;
    }

    .modal-content {
        margin: 20px;
    }
}
</style>
