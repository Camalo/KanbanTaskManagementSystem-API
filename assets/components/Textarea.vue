<template>
    <textarea rows="10" class="input" :type="type" :name="name" :id="id" :placeholder="placeholder"></textarea>
    <label class="form__message" :for="id">Обязательное поле для заполнения</label>
</template>

<script setup>
const props = defineProps({
    type: { type: String, default: 'text' },
    name: { type: String, default: 'input01' },
    id: { type: String, default: 'input01' },
    placeholder: { type: String, default: '' }
})
</script>