<template>
    <label class="check">
        <input type="checkbox" :name="name" :id="id">
        <span class="check__box"></span>
        <span class="check__label">{{ label }}</span>
    </label>
</template>

<script setup>
const props = defineProps({
    name: { type: String, default: 'check01' },
    id: { type: String, default: 'check01' },
    label: { type: String, default: '' }
})
</script>