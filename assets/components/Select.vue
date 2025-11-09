<template>
  <div class="select" ref="selectRef" :class="{ 'is-open': isOpen, 'is-focused': isFocused }">
    <div
      class="select__trigger"
      @click="toggle"
      @focus="isFocused = true"
      @blur="isFocused = false"
      tabindex="0"
    >
      <span class="select__value">
        {{ selected?.label || placeholder }}
      </span>
      <svg class="select__icon" width="16" height="16" viewBox="0 0 24 24">
        <path d="M7 10l5 5 5-5z" fill="currentColor"/>
      </svg>
    </div>

    <ul class="select__dropdown" v-if="isOpen">
      <li
        v-for="option in options"
        :key="option.value"
        class="select__option"
        :class="{ active: selected?.value === option.value }"
        @click="select(option)"
      >
        {{ option.label }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps({
  options: { type: Array, required: true },
  placeholder: { type: String, default: 'Выберите значение' },
  modelValue: { type: [String, Number, Object], default: null },
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const isFocused = ref(false)
const selected = ref(props.modelValue)
const selectRef = ref(null)

function toggle() {
  isOpen.value = !isOpen.value
}

function select(option) {
  selected.value = option
  emit('update:modelValue', option)
  isOpen.value = false
}

function handleClickOutside(e) {
  if (selectRef.value && !selectRef.value.contains(e.target)) {
    isOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside))
</script>
