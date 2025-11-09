<template>
    <div class="card-detail">
        <div class="card-detail__header">
            <div class="card-detail__project">Selevium разработка</div>
            <div class="card-detail__badge">Новая</div>
        </div>
        <div class="card-detail__body">
            <form class="form" action="" method="post">
                <div class="form__row">
                    <Input type="text" id="input01" placeholder="Название"></Input>
                </div>
                <div class="form__row">
                    <div class="form__column form__column-full">
                        <div class="form__label">Владелец:</div>
                        <Select v-model="selected1" :options="options1"></Select>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__column form__column-full">
                        <div class="form__label">Исполнитель:</div>
                        <Select v-model="selected2" :options="options2"></Select>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__column form__column-full">
                        <div class="form__label">Приоритет:</div>
                        <Select v-model="selected3" :options="options3"></Select>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__column form__column-full">
                        <div class="form__label">Выполнить до:</div>
                        <Select v-model="selected4" :options="options4"></Select>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__column form__column-full">
                        <Textarea type="text" id="input02" placeholder="Описание"></Textarea>
                    </div>
                </div>
                <div class="form__row">
                    <Submit buttonText="Создать"></Submit>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Input from '../components/Input.vue';
import Select from '../components/Select.vue';
import Submit from '../components/Submit.vue';
import Textarea from '../components/Textarea.vue';

const options1 = [
    { label: 'Павел Крамской', value: '1' },
    { label: 'Анна Павлова', value: '2' }
]
const selected1 = ref(null)

const options2 = [
    { label: 'Не выбрано', value: '0' },
    { label: 'Павел Крамской', value: '1' },
    { label: 'Анна Павлова', value: '2' }
]
const selected2 = ref(null)

const options3 = [
    { label: 'Нормальный', value: 'normal' },
    { label: 'Низкий', value: 'low' },
    { label: 'Высокий', value: 'high' }
]

const selected3 = ref(null)

const options4 = [
    { label: 'Завтра', value: 'normal' },
    { label: 'Послезавтра', value: 'low' },
]

const selected4 = ref(null)



</script>