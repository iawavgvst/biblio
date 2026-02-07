<template>
    <App>
        <div class="dashboard-page">
            <div class="header-container">
                <h1>Admin Dashboard</h1>
                <p class="subtitle">
                    Welcome to the administration panel
                </p>
            </div>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon"><font-awesome-icon icon="users"/></div>
                    <div class="stat-content">
                        <h3>Total Users</h3>
                        <p class="stat-value">{{ stats.totalUsers }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><font-awesome-icon icon="pen"/></div>
                    <div class="stat-content">
                        <h3>Admin Users</h3>
                        <p class="stat-value">{{ stats.adminUsers }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><font-awesome-icon icon="user"/></div>
                    <div class="stat-content">
                        <h3>Regular Users</h3>
                        <p class="stat-value">{{ stats.regularUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="recent-users">
                <div class="section-header">
                    <h2>Recent Users</h2>
                    <Link href="/admin/users" class="view-all-link">
                        View All Users →
                    </Link>
                </div>
                <div class="users-table">
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in recentUsers" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                    <span :class="['role-badge', user.role]">
                                        {{ user.role }}
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
import {Link, router} from '@inertiajs/vue3';
import App from '../../layouts/App.vue';
import { defineProps } from 'vue';
import { SButton } from 'startup-ui'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalUsers: 0,
            adminUsers: 0,
            regularUsers: 0
        })
    },
    recentUsers: {
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
}

function deleteUser(user) {
    if (confirm(`Вы уверены, что хотите удалить ${user.name}?`)) {
        router.delete(`/admin/users/${user.id}`)
    }
}
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

.recent-users {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h2 {
    font-size: 28px;
    margin: 0;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.view-all-link {
    color: #005bb5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.view-all-link:hover {
    color: #003d82;
    text-decoration: underline;
}

.users-table {
    overflow-x: auto;
}

.users-table table {
    width: 100%;
    border-collapse: collapse;
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
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
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

.quick-actions {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.quick-actions h2 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    text-align: center;
}

.action-btn:hover {
    background: #e9ecef;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: #005bb5;
}

.action-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.action-btn span {
    font-size: 16px;
    font-weight: 500;
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

    .stats-container {
        grid-template-columns: 1fr;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .users-table {
        font-size: 14px;
    }

    .users-table th,
    .users-table td {
        padding: 8px 12px;
    }

    .navigation-links {
        grid-template-columns: 1fr;
    }
}
</style>
