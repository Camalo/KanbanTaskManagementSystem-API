<template>
    <main>
        <div class="container">
            <div class="board">
                <div class="board__header">
                    <Select v-model="selected" :options="options"></Select>
                    <Button buttonText="Создать задачу"></Button>
                </div>

                <div class="board__body">
                    <div class="column">
                        <div class="column__header">Новые</div>
                        <div class="column__body">
                            <Card title="Задача 1" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." cardType="card-major"
                                badgeType="card__badge-major" @open="openTask" @edit="onEdit" @delete="onDelete"></Card>
                        </div>
                    </div>
                    <div class="column">
                        <div class="column__header">В работе</div>
                        <div class="column__body">
                            <Card title="Задача 2" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." @open="openTask" @edit="onEdit"
                                @delete="onDelete"></Card>
                            <Card title="Задача 3" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." @open="openTask" @edit="onEdit"
                                @delete="onDelete"></Card>
                        </div>
                    </div>
                    <div class="column">
                        <div class="column__header">Готово</div>
                        <div class="column__body">
                            <Card title="Задача 4" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." cardType="card-major"
                                badgeType="card__badge-major" @open="openTask" @edit="onEdit" @delete="onDelete"></Card>
                            <Card title="Задача 5" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." @open="openTask" @edit="onEdit"
                                @delete="onDelete"></Card>
                            <Card title="Задача 6" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." cardType="card-minor"
                                badgeType="card__badge-minor" @open="openTask" @edit="onEdit" @delete="onDelete"></Card>
                        </div>
                    </div>
                    <div class="column">
                        <div class="column__header">Отмененные</div>
                        <div class="column__body">
                            <Card title="Задача 7" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." cardType="" badgeType="" @open="openTask"
                                @edit="onEdit" @delete="onDelete"></Card>
                            <Card title="Задача 8" label="27.11.2025" text=" Как принято считать, интерактивные прототипы набирают популярность среди
                                        определенных слоев населения." cardType="" badgeType="" @open="openTask"
                                @edit="onEdit" @delete="onDelete"></Card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Modal: показывает только id сейчас -->
    
</template>

<script setup>
import { ref } from 'vue';
import Button from '../components/Button.vue';
import Card from '../components/Card.vue';
import Select from '../components/Select.vue';

const options = [
    { label: 'Все', value: 'all' },
    { label: 'Отмененные', value: 'canceled' },
    { label: 'Backlog', value: 'backlog' }
]

const selected = ref(null)


const isModalOpen = ref(false)
const activeTaskId = ref(null)
const modalMode = ref('view') // 'view' | 'create' | 'edit'

// открыть модалку с id (карточка эмитит id)
function openTask(id) {
    activeTaskId.value = id
    modalMode.value = 'view'
    isModalOpen.value = true
}


// закрыть
function closeModal() {
    isModalOpen.value = false
    activeTaskId.value = null
}

// примеры обработчиков меню
function onEdit(id) {
    activeTaskId.value = id
    modalMode.value = 'edit'
    isModalOpen.value = true
}
function onDelete(id) {
    // пока просто удалить локально
    for (const col of columns.value) {
        const idx = col.tasks.findIndex(t => t.id === id)
        if (idx >= 0) { col.tasks.splice(idx, 1); break }
    }
}

</script>