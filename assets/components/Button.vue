<template>
    <button class="button" @click="click">{{ buttonText }}</button>
</template>

<script setup>

const props = defineProps({
  buttonText: { type: String, default: 'Войти' },
})

const click = () => {
  alert('Кнопка нажата!')
}

// defineEmits(['update:modelValue'])
</script>