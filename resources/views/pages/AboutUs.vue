<template>
    <App>
        <div class="about-us">
            <div class="header-container">
                <h1>About Us</h1>
                <p class="subtitle">
                    Welcome!
                </p>
            </div>
            <div class="content-container">
                <section class="about-section">
                    <h2>Our history</h2>
                    <p>
                        Our library was founded in 2026 with the aim of creating a modern digital space for book lovers.
                        We strive to make reading accessible and fun for all ages.
                    </p>
                    <p>
                        During our existence, we have collected a unique collection of works of various genres — from classical literature to modern bestsellers.
                        Each book in our catalog has been carefully selected by our experts.
                    </p>
                </section>
            </div>
        </div>
        <div class="navigation-links">
            <Link href="/" class="nav-link">
                <span>Home Page</span>
            </Link>
            <Link href="/profile" class="nav-link">
                <span>Profile</span>
            </Link>
            <Link href="/scroll-page" class="nav-link">
                <span>Scroll Page</span>
            </Link>
        </div>
    </App>
</template>

<script setup>
import App from '../layouts/App.vue'
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
.about-us {
    padding: 20px;
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
}

.subtitle {
    font-size: 18px;
    color: #666;
    font-style: italic;
}

.content-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

section {
    padding: 25px;
    border-radius: 10px;
    background-color: white;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #444;
}

p {
    line-height: 1.6;
    color: #555;
    margin-bottom: 15px;
}

.features-list li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px;
    background-color: #f8f9fa;
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

    h2 {
        font-size: 24px;
    }

    .navigation-links {
        grid-template-columns: 1fr;
    }
}
</style>
