<template>
    <div class="modal-backdrop" @click.self="close">
        <div class="modal-content">
            <button class="modal-close" @click="close">✕</button>
            <slot />
        </div>
    </div>
</template>

<script setup>
import { defineEmits } from 'vue';
const emit = defineEmits(['close'])
function close() { emit('close') }
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1000;
}

.modal-content {
    width: 720px;
    max-width: 95%;
    background: #fff;
    border-radius: 8px;
    padding: 16px;
    position: relative;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

.modal-close {
    position: absolute;
    right: 8px;
    top: 8px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 16px;
}
</style>
