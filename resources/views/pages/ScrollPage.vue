<template>
    <App>
        <div class="scroll-page">
            <div class="header-container">
                <h1>Scroll Page</h1>
                <p class="subtitle">
                    Demonstration of scroll preservation with Inertia.js
                </p>
            </div>
            <div class="content-section">
                <h2>Understanding preserveScroll & preserveState</h2>
                <div class="explanation">
                    <h3>What happens when you use preserveScroll:</h3>
                    <ul>
                        <li><strong>With preserveScroll:</strong> The page maintains its scroll position after navigation</li>
                        <li><strong>Without preserveScroll:</strong> The page scrolls back to the top after navigation</li>
                    </ul>
                    <h3>What happens when you use preserveState:</h3>
                    <ul>
                        <li><strong>With preserveState:</strong> Component state (like form inputs, counters) is preserved</li>
                        <li><strong>Without preserveState:</strong> Component state is reset to initial values</li>
                    </ul>
                    <div class="demo-state">
                        <h3>State Preservation Demo</h3>
                        <div class="state-controls">
                            <SButton class="btn-small" @click="incrementCounter">
                                Increment Counter: {{ counter }}
                            </SButton>
                            <input
                                type="text"
                                v-model="textInput"
                                placeholder="Type something here..."
                                class="state-input"
                            />
                        </div>
                        <p class="state-hint">
                            This counter and input field demonstrate state preservation.
                            Try refreshing with and without preserveState to see the difference.
                        </p>
                    </div>
                </div>
                <div class="controls-container">
                    <div class="control-buttons">
                        <SButton class="btn" @click="refreshWithPreserve">
                            Refresh with preserveScroll & preserveState
                        </SButton>
                        <SButton class="btn btn-secondary" @click="refreshWithoutPreserve">
                            Refresh without preserveScroll & preserveState
                        </SButton>
                        <SButton class="btn btn-info" @click="scrollToBottom">
                            Scroll to Bottom
                        </SButton>
                    </div>
                    <div class="preserve-info">
                        <div class="info-card">
                            <h3>Current Scroll Position</h3>
                            <p class="scroll-position">{{ scrollPosition }}px</p>
                        </div>
                        <div class="info-card">
                            <h3>Last Refresh</h3>
                            <p class="refresh-time">{{ lastRefresh }}</p>
                        </div>
                        <div class="info-card">
                            <h3>Refresh Count</h3>
                            <p class="refresh-count">{{ refreshCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="long-content">
                    <h2>Sample Content for Scrolling</h2>
                    <div v-for="section in 20" :key="section" class="content-block">
                        <h3>Section {{ section }}</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                        <p>
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                            eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                            sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <div class="section-marker" :id="'section-' + section">
                            <span>Marker for Section {{ section }}</span>
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
                <Link href="/dashboard" class="nav-link">
                    <span>Dashboard</span>
                </Link>
                <Link href="/profile" class="nav-link">
                    <span>Profile</span>
                </Link>
            </div>
            <div class="scroll-indicator" :class="{ 'visible': showScrollIndicator }">
                Scroll Position: {{ scrollPosition }}px
            </div>
        </div>
    </App>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { SButton } from 'startup-ui';
import App from '../layouts/App.vue';

const scrollPosition = ref(0);
const showScrollIndicator = ref(false);
const lastRefresh = ref(new Date().toLocaleTimeString());
const refreshCount = ref(0);
const counter = ref(0);
const textInput = ref('');

const handleScroll = () => {
    scrollPosition.value = window.scrollY
    showScrollIndicator.value = window.scrollY > 100
};

const refreshWithPreserve = () => {
    refreshCount.value++
    lastRefresh.value = new Date().toLocaleTimeString()

    router.visit('/scroll-page', {
        preserveScroll: true,
        preserveState: true,
    });
};

const refreshWithoutPreserve = () => {
    refreshCount.value++
    lastRefresh.value = new Date().toLocaleTimeString()

    router.visit('/scroll-page', {
        preserveScroll: false,
        preserveState: false,
    });
};

const scrollToBottom = () => {
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: 'smooth'
    });
};

const incrementCounter = () => {
    counter.value++
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
    handleScroll()
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
});
</script>

<style scoped>
.scroll-page {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
    position: relative;
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

.controls-container {
    margin-bottom: 40px;
}

.control-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 30px;
    justify-content: center;
}

.btn {
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 200px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.preserve-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.info-card h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #666;
    font-weight: 500;
}

.scroll-position {
    font-size: 32px;
    font-weight: bold;
    color: #005bb5;
    margin: 0;
}

.refresh-time {
    font-size: 24px;
    font-weight: 500;
    color: #28a745;
    margin: 0;
}

.refresh-count {
    font-size: 32px;
    font-weight: bold;
    color: #dc3545;
    margin: 0;
}

.content-section {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
    margin-bottom: 40px;
}

.content-section h2 {
    font-size: 32px;
    margin-bottom: 30px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.explanation {
    margin-bottom: 40px;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.explanation h3 {
    font-size: 20px;
    margin: 25px 0 15px 0;
    color: #444;
}

.explanation h3:first-child {
    margin-top: 0;
}

.explanation ul {
    margin: 0 0 20px 20px;
    padding: 0;
}

.explanation li {
    margin-bottom: 10px;
    line-height: 1.6;
    color: #555;
}

.demo-state {
    margin-top: 30px;
    padding: 25px;
    background: white;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.state-controls {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.btn-small {
    padding: 10px 20px;
    font-size: 14px;
    background: #005bb5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-small:hover {
    background: #004a9c;
}

.state-input {
    flex: 1;
    min-width: 200px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
}

.state-input:focus {
    outline: none;
    border-color: #005bb5;
    box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.2);
}

.state-hint {
    font-size: 14px;
    color: #666;
    font-style: italic;
    margin: 0;
}

.long-content {
    margin-top: 40px;
}

.content-block {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid #e0e0e0;
}

.content-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.content-block h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.content-block p {
    line-height: 1.6;
    color: #555;
    margin-bottom: 15px;
}

.section-marker {
    background: #e9ecef;
    padding: 15px;
    border-radius: 6px;
    margin-top: 20px;
    text-align: center;
    color: #666;
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
    background: #005bb5;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 91, 181, 0.3);
}

.nav-link span:last-child {
    font-size: 16px;
    font-weight: 500;
}

.scroll-indicator {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(0, 91, 181, 0.9);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

.scroll-indicator.visible {
    opacity: 1;
}

@media (max-width: 768px) {
    h1 {
        font-size: 36px;
    }

    .control-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        min-width: auto;
    }

    .preserve-info {
        grid-template-columns: 1fr;
    }

    .state-controls {
        flex-direction: column;
    }

    .state-input {
        min-width: auto;
        width: 100%;
    }

    .nav-grid {
        grid-template-columns: 1fr;
    }
}
</style>
