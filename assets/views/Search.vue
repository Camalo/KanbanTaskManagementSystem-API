<template>
    <main>
        <div class="container">
            <form class="form" action="" method="get">
                <div class="form__row">
                    <Input type="text" id="input01" placeholder="Поиск"></Input>
                    <Submit buttonText="Найти"></Submit>
                </div>
            </form>
        </div>
        <div class="container">

        </div>
    </main>
</template>

<script setup>
import Input from '../components/Input.vue';
import Submit from '../components/Submit.vue';
</script>