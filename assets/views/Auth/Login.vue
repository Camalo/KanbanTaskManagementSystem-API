<template>
    <main>
        <div class="container container-center">
            <div class="form-container">
                <div class="title">Войти</div>
                <form class="form" action="" method="post">
                    <div class="form__column">
                        <Input type="email" id="input01" placeholder="Email"></Input>
                    </div>
                    <div class="form__column">
                        <Input type="password" id="input02" placeholder="Пароль"></Input>
                    </div>
                    <div class="form__row form__row-extended">
                        <Check name="check01" id="check01" label="Запомнить меня"></Check>
                        <a href="#" class="form__link">Забыли пароль?</a>
                    </div>
                    <div class="form__row">
                        <Submit buttonText="Войти"></Submit>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<script setup>
import Check from '../../components/Check.vue';
import Input from '../../components/Input.vue';
import Submit from '../../components/Submit.vue';
</script>