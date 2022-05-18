<template>
  <div class="login">
    <div class="login__card">
      <div class="card__header mb-11">
        <div class="header__logo mb-5">
          <v-img :src="require('@/assets/logo-2.svg')" max-width="100" height="38"/>
        </div>
        <div class="header__content">Добро пожаловать</div>
      </div>
      <div class="card__body">
        <v-text-field outlined label="Логин" placeholder="e.g. john@gmail.com" persistent-placeholder />
        <v-text-field
          outlined
          label="Пароль"
          placeholder="Xsw36Fg8jK123"
          persistent-placeholder
          :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          :type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
        />
      </div>
      <div class="card__buttons">
        <v-btn color="primary" class="mb-5" block x-large>Войти</v-btn>
        <v-btn color="primary" text block>Забыли пароль?</v-btn>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'

@Component({
  data() {
    return {
      showPassword: false
    }
  }
})
export default class Login extends Vue {
}
</script>

<style scoped lang="scss">
.login {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  .login__card {
    width: 538px;
    padding: 55px 85px;

    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 10px;

    .header__logo {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .header__content {
      display: flex;
      justify-content: center;
      align-items: center;

      font-family: Montserrat, serif;
      font-size: 24px;
      font-weight: 400;
      line-height: 29px;
      letter-spacing: 0;
      text-align: left;

    }
  }
}
</style>
