<template>
    <div :class="['card', cardType]" @click="onOpen" draggable="true" @dragstart="onDragStart">
        <div class="card__header">
            <div class="card__row">
                <img class="card__image" src="../icons/default_profile.png" />
                <div class="card__label">{{ label }}</div>
            </div>
            <img class="card__icon" @click.stop="toggleMenu" src="../icons/context_menu.svg" alt="">
            <ContextMenu :open="menuOpen" @edit="emitEdit" @delete="emitDelete" />
        </div>
        <div class="card__body">
            <div class="card__title">
                {{ title }}
            </div>
            <div class="card__text">
                {{ text }}
            </div>
            <div :class="['card__badge', badgeType]">
                {{ badgeTitle }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import ContextMenu from './ContextMenu.vue'

const props = defineProps({
    title: { type: String, required: true },
    label: { type: String, default: '' },
    text: { type: String, default: '' },
    image: { type: String, default: '../icons/default_profile.png' },
    cardType: { type: String, default: 'card-ordinary' },
    badgeType: { type: String, default: 'card__badge-ordinary' },
    badgeTitle: { type: String, default: 'Нормальный' },
})

const emit = defineEmits(['open','dragstart','edit','delete'])
const menuOpen = ref(false)
function toggleMenu(){ menuOpen.value = !menuOpen.value }
function onOpen(){ emit('open', props.id) }
function onDragStart(e){ e.dataTransfer.setData('text/plain', String(props.id)); emit('dragstart', props.id) }
function emitEdit(){ emit('edit', props.id); menuOpen.value = false }
function emitDelete(){ emit('delete', props.id); menuOpen.value = false }
</script>