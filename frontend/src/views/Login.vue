<template>
  <div class="login">
    <validation-observer v-slot="{ invalid }" ref="observer">
      <div class="login__card">
        <div class="card__header mb-11">
          <div class="header__logo mb-5">
            <v-img :src="require('@/assets/logo-2.svg')" max-width="100" height="38" />
          </div>
          <div class="header__content">Добро пожаловать</div>
        </div>
        <div class="card__body">
          <validation-provider rules="required|email" v-slot="{ errors }">
            <v-text-field
              v-model="login"
              :disabled="loading"
              :error-messages="errors"
              outlined
              label="Логин"
              placeholder="e.g. john@gmail.com"
              persistent-placeholder
            />
          </validation-provider>
          <validation-provider vid="password" rules="required" v-slot="{ errors }">
            <v-text-field
              v-model="password"
              :disabled="loading"
              :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              :type="showPassword ? 'text' : 'password'"
              :error-messages="errors"
              outlined
              label="Пароль"
              placeholder="Xsw36Fg8jK123"
              persistent-placeholder
              @click:append="showPassword = !showPassword"
            />
          </validation-provider>
        </div>
        <div class="card__buttons">
          <v-btn :disabled="loading || invalid" color="primary" class="mb-5" block x-large @click="tryLogin">Войти</v-btn>
          <v-btn :disabled="loading" color="primary" text block>Забыли пароль?</v-btn>
        </div>
      </div>
    </validation-observer>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import AuthRepository from "@/repositories/AuthRepository";
import {ValidationObserver} from "vee-validate";

@Component({
  components: { ValidationObserver }
})
export default class Login extends Vue {
  $refs!: {
    observer: InstanceType<typeof ValidationObserver>
  }

  private login = ''
  private password = ''
  private showPassword = false
  private loading = false

  public async tryLogin(): Promise<void> {
    this.loading = true;
    try {
      const {data} = await AuthRepository.login(this.login, this.password);
      localStorage.setItem('jwt', data.access_token);
    } catch (e) {
      this.$refs.observer.setErrors({
        'password': ['Неверный логин или пароль']
      });
    }
    this.loading = false
  }
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
