<template>
    <div class="register-page">
        <div class="content-container">
            <section class="register-section">
                <h2>Создание аккаунта</h2>
                <form @submit.prevent="submit" class="register-form">
                    <SFormRow class="form-group">
                        <label for="name">Имя</label>
                        <SInput
                            type="text"
                            id="name"
                            v-model="form.name"
                            placeholder="Введите ваше имя"
                            class="form-input"
                            :class="{ 'error': form.errors.name }"
                        />
                        <div v-if="form.errors.name" class="error-message">
                            {{ form.errors.name }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-group">
                        <label for="email">Электронная почта</label>
                        <SInput
                            type="email"
                            id="email"
                            v-model="form.email"
                            placeholder="Введите ваш email"
                            class="form-input"
                            :class="{ 'error': form.errors.email }"
                        />
                        <div v-if="form.errors.email" class="error-message">
                            {{ form.errors.email }}
                        </div>
                    </SFormRow>
                    <SFormRow class="form-row">
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <SInput
                                type="password"
                                id="password"
                                v-model="form.password"
                                placeholder="Создайте пароль"
                                class="form-input"
                                :class="{ 'error': form.errors.password }"
                            />
                            <div v-if="form.errors.password" class="error-message">
                                {{ form.errors.password }}
                            </div>
                        </div>
                        <SFormRow class="form-group">
                            <label for="confirmPassword">Подтверждение пароля</label>
                            <SInput
                                type="password"
                                id="confirmPassword"
                                v-model="form.password_confirmation"
                                placeholder="Повторите пароль"
                                class="form-input"
                                :class="{ 'error': form.errors.password_confirmation }"
                            />
                            <div v-if="form.errors.password_confirmation" class="error-message">
                                {{ form.errors.password_confirmation }}
                            </div>
                        </SFormRow>
                    </SFormRow>
                    <button type="submit" class="submit-btn" :class="{ 'button-loading': form.processing }"
                            :disabled="form.processing">
                        {{ form.processing ? 'Регистрация...' : 'Зарегистрироваться' }}
                    </button>
                    <div class="login-link">
                        <p>Уже есть аккаунт?
                            <Link href="/login" class="link">Войти</Link>
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { SInput, SButton, SFormRow } from 'startup-ui'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onSuccess: () => {
            console.log('Регистрация успешно завершена');
        },
        onError: (errors) => {
            console.log('Ошибки регистрации:', errors);
        },
        preserveScroll: true
    });
};
</script>

<style scoped>
.register-page {
    padding: 20px;
    margin: 0 auto;
    max-width: 900px;
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

.register-section {
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

h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #444;
}

.register-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
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

.terms-error {
    margin-top: -10px;
    margin-bottom: 10px;
}

.form-options {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 10px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    color: #555;
    line-height: 1.4;
}

.checkbox-label input[type="checkbox"] {
    margin-top: 3px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    flex-shrink: 0;
}

.terms-link {
    color: #005bb5;
    text-decoration: none;
    transition: color 0.3s ease;
}

.terms-link:hover {
    color: #003d82;
    text-decoration: underline;
}

.password-requirements {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    margin-top: 10px;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.password-requirements li {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
    position: relative;
    padding-left: 25px;
}

.password-requirements li::before {
    content: "✗";
    position: absolute;
    left: 0;
    color: #dc3545;
    font-weight: bold;
}

.password-requirements li.valid::before {
    content: "✓";
    color: #28a745;
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
    margin-top: 20px;
}

.submit-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #003d82 0%, #002a5c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 91, 181, 0.2);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.login-link {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.login-link p {
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

.navigation-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-top: 40px;
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

    .register-section {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .navigation-links {
        grid-template-columns: 1fr;
    }

    .register-page {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .header-container {
        padding: 20px;
    }

    h1 {
        font-size: 28px;
    }

    .subtitle {
        font-size: 16px;
    }

    .form-input {
        padding: 10px 12px;
    }

    .submit-btn {
        padding: 12px 16px;
    }

    .password-requirements {
        padding: 15px;
    }

    .password-requirements li {
        font-size: 13px;
    }
}
</style>
