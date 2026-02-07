<template>
    <div class="app">
        <nav class="navbar">
            <div class="nav-container">
                <h1 class="nav-title">Through The Worlds</h1>
                <div class="nav-links">
                    <Link href="/" class="nav-link">
                        Home Page
                    </Link>
                    <Link href="/about" class="nav-link">
                        About Us
                    </Link>
                    <Link href="/profile" class="nav-link">
                        Profile
                    </Link>
                    <Link href="/scroll-page" class="nav-link">
                        Scroll Page
                    </Link>
                    <template v-if="!$page.props.auth?.user">
                        <Link href="/register" class="nav-link">
                            Register
                        </Link>
                        <Link href="/login" class="nav-link">
                            Login
                        </Link>
                    </template>
                    <template v-else>
                        <form @submit.prevent="logout" class="logout-form">
                            <button type="submit" class="logout-button">Выйти</button>
                        </form>
                    </template>
                </div>
            </div>
        </nav>
        <div class="page-container">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

function logout() {
    router.post('/logout')
}
</script>

<style scoped>
.app {
    margin: auto;
    padding: 0;
    height: 100%;
    width: 100%;
}

.navbar {
    background: linear-gradient(135deg, #005bb5 0%, #003d82 100%);
    padding: 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
}

.nav-container {
    max-width: 1350px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.nav-title {
    font-size: 28px;
    margin: 0;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.nav-links {
    display: flex;
    gap: 5px;
    align-items: center;
}

.nav-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    color: white;
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.logout-form {
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
}

.logout-button {
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

.logout-button:hover {
    background-color: #c82333;
}

.page-container {
    padding: 0;
    margin: 0;
}
</style>
