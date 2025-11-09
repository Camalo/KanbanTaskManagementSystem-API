<template>
    <input class="button" type="submit" :value="buttonText" />
</template>

<script setup>
const props = defineProps({
    buttonText: { type: String, default: '' }
})
</script>
