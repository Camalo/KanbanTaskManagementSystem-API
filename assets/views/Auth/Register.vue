<template>
    <main>
        <div class="container container-center">
            <div class="form-container">
                <div class="title">Войти</div>
                <form class="form" action="" method="post">
                    <div class="form__column">
                        <Input type="text" id="input01" placeholder="Имя"></Input>
                    </div>
                      <div class="form__column">
                        <Input type="text" id="input02" placeholder="Фамилия"></Input>
                    </div>
                      <div class="form__column">
                        <Input type="text" id="input03" placeholder="Отчество"></Input>
                    </div>
                      <div class="form__column">
                        <Input type="email" id="input04" placeholder="Email"></Input>
                    </div>
                    <div class="form__column">
                        <Input type="password" id="input05" placeholder="Пароль"></Input>
                    </div>
                    <div class="form__column">
                        <Input type="password" id="input06" placeholder="Повторите пароль"></Input>
                    </div>
                    <div class="form__row">
                        <Submit buttonText="Создать аккаунт"></Submit>
                    </div>
                    <div class="form__row">
                         <a href="#" class="form__link">Уже есть аккаунт?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<script setup>
import Input from '../../components/Input.vue';
import Submit from '../../components/Submit.vue';
</script>