<template>
    <div class="ctx-menu" v-if="open" @click.stop>
        <button @click="$emit('edit')">Редактировать</button>
        <button @click="$emit('history')">История изменений</button>
        <button @click="$emit('delete')">Удалить</button>
    </div>
</template>

<script setup>
const props = defineProps({ open: Boolean })
const emit = defineEmits(['edit', 'history', 'delete'])
</script>

<style scoped>
.ctx-menu {
    background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    padding: 6px;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.ctx-menu button {
    background: transparent;
    border: none;
    text-align: left;
    padding: 6px 8px;
    cursor: pointer;
}

.ctx-menu button:hover {
    background: #f5f7ff;
}
</style>
