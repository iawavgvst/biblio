<template>
    <transition name="dialog-slide-fade" appear>
        <div class="dialog-form-overlay" v-if="show" @click.self="closeDialog">
            <div class="dialog-form">
                <div class="form-header">
                    <div>
                        <slot name="title">
                            <h3>Заголовок</h3>
                        </slot>
                    </div>
                  <SButton class="close-btn" @click="closeDialog">×</SButton>
                </div>
                <div class="dialog-content">
                    <slot></slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { SButton } from 'startup-ui'

defineProps({
    show: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['close']);

const closeDialog = () => {
    emit('close');
};
</script>

<style scoped>
.dialog-form-overlay {
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

.dialog-form {
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

.dialog-slide-fade-enter-active .dialog-form-overlay,
.dialog-slide-fade-leave-active .dialog-form-overlay {
    transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.dialog-slide-fade-enter-to .dialog-form-overlay,
.dialog-slide-fade-leave-from .dialog-form-overlay {
    background-color: rgba(0, 0, 0, 0.5);
}

.dialog-slide-fade-enter-from .dialog-form-overlay,
.dialog-slide-fade-leave-to .dialog-form-overlay {
    background-color: rgba(0, 0, 0, 0);
}

.dialog-slide-fade-enter-active .dialog-form,
.dialog-slide-fade-leave-active .dialog-form {
    transition: all 1s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.dialog-slide-fade-enter-from .dialog-form {
    transform: translateY(100px) scale(0.95);
    opacity: 0;
}

.dialog-slide-fade-enter-to .dialog-form {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.dialog-slide-fade-leave-from .dialog-form {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.dialog-slide-fade-leave-to .dialog-form {
    transform: translateY(100px) scale(0.95);
    opacity: 0;
}
</style>