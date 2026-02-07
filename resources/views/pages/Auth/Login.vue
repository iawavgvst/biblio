<template>
    <div class="login-page">
        <div class="content-container">
            <section class="login-section">
                <h2>Авторизация</h2>
                <form @submit.prevent="submit" class="login-form">
                    <SFormRow class="form-group">
                        <label for="email">Электронная почта</label>
                        <SInput
                            type="email"
                            id="email"
                            v-model="form.email"
                            placeholder="Введите ваш email"
                            required
                            class="form-input"
                            :class="{ 'error': form.errors.email }"
                        />
                        <div v-if="form.errors.email" class="error-message">
                            {{ form.errors.email }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="password">Пароль</label>
                        <SInput
                            type="password"
                            id="password"
                            v-model="form.password"
                            placeholder="Введите ваш пароль"
                            required
                            class="form-input"
                            :class="{ 'error': form.errors.password }"
                        />
                        <div v-if="form.errors.password" class="error-message">
                            {{ form.errors.password }}
                        </div>
                    </SFormRow>
                    <div class="form-options">
                        <label class="checkbox-label">
                            <SInput type="checkbox" v-model="form.remember"/>
                            <span>Запомнить меня</span>
                        </label>
                    </div>
                    <button type="submit" class="submit-btn" :disabled="form.processing">
                        {{ form.processing ? 'Вход...' : 'Войти' }}
                    </button>
                    <div class="register-link">
                        <p>Нет аккаунта?
                            <Link href="/register" class="link">Зарегистрироваться</Link>
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { SInput, SFormRow } from 'startup-ui';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onSuccess: () => {
            console.log('Вход выполнен успешно');
        },
        onError: (errors) => {
            console.log('Ошибки входа:', errors);
        },
        preserveScroll: true
    });
};
</script>

<style scoped>
.login-page {
    padding: 20px;
    margin: 0 auto;
    max-width: 800px;
    font-family: "Tahoma", "Arial", "Arabic Transparent", sans-serif;
}

h1 {
    font-size: 48px;
    margin-bottom: 10px;
    text-transform: uppercase;
}

.content-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.login-section {
    padding: 30px;
    border-radius: 10px;
    background-color: white;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

h2 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #333;
    border-bottom: 2px solid #005bb5;
    padding-bottom: 10px;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 500;
    color: #444;
    font-size: 16px;
}

.form-input {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: #005bb5;
    box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.1);
}

.form-input.error {
    border-color: #dc3545;
}

.form-input.error:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.1);
}

.form-input::placeholder {
    color: #999;
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 4px;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #555;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.submit-btn {
    padding: 14px 20px;
    background: linear-gradient(135deg, #005bb5 0%, #003d82 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.submit-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #003d82 0%, #002a5c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 91, 181, 0.2);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.register-link {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.register-link p {
    color: #666;
    font-size: 15px;
}

.link {
    color: #005bb5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.link:hover {
    color: #003d82;
    text-decoration: underline;
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

    .login-section {
        padding: 20px;
    }

    .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .login-page {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 28px;
    }

    .form-input {
        padding: 10px 12px;
    }

    .submit-btn {
        padding: 12px 16px;
    }
}
</style>
