<script setup lang="ts">
import AuthRepository from '@/repositories/AuthRepository'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/valibot'
import { object, string, email, minLength } from 'valibot'
import { useNotifierStore } from '@/stores/notifier'
import { useRoute, useRouter } from 'vue-router'
import logo from '@/assets/logo-2.svg'
import { ref } from 'vue'

const route = useRoute()
const router = useRouter()
const { notify } = useNotifierStore()

const { errors, defineField, validate, setErrors } = useForm({
  validationSchema: toTypedSchema(object({
    login: string('Обязательное поле', [minLength(1, 'Обязательное поле'), email('Некорректная почта')]),
    password: string('Обязательное поле', [minLength(1, 'Обязательное поле')]),
  })),
})
const [login, loginProps] = defineField('login', state => ({
  validateOnModelUpdate: state.errors.length > 0,
}))
const [password, passwordProps] = defineField('password', state => ({
  validateOnModelUpdate: state.errors.length > 0,
}))

const loading = ref(false)
const showPassword = ref(false)

const tryLogin = async () => {
  if (!(await validate()).valid) {
    notify('Проверьте красные поля', 'error')
    return
  }

  loading.value = true
  try {
    const { data } = await AuthRepository.login(login.value, password.value)
    localStorage.setItem('jwt', data.access_token)

    const returnTo = route.query.returnto as string | undefined

    await router.push(returnTo ?? '/menus')
  } catch (e) {
    setErrors({
      password: ['Неверный логин или пароль'],
    })
  }
  loading.value = false
}
</script>

<template>
  <div class="login">
    <div class="login__card">
      <div class="card__header mb-11">
        <div class="header__logo mb-5">
          <v-img :src="logo" max-width="100" height="38" />
        </div>
        <div class="header__content">Добро пожаловать</div>
      </div>
      <div class="card__body">
        <v-text-field
          v-model="login"
          v-bind="loginProps"
          :disabled="loading"
          :error-messages="errors.login"
          variant="outlined"
          persistent-placeholder
          label="Логин"
          placeholder="e.g. john@gmail.com"
        />
        <v-text-field
          v-model="password"
          v-bind="passwordProps"
          :disabled="loading"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          :type="showPassword ? 'text' : 'password'"
          :error-messages="errors.password"
          variant="outlined"
          persistent-placeholder
          label="Пароль"
          placeholder="Xsw36Fg8jK123"
          @click:append-inner="showPassword = !showPassword"
        />
      </div>
      <div class="card__buttons">
        <v-btn :disabled="loading" color="primary" class="mb-5" block @click="tryLogin">Войти
        </v-btn>
        <v-btn :disabled="loading" color="primary" text block>Забыли пароль?</v-btn>
      </div>
    </div>
  </div>
</template>

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
    background: var(--v-background-base);

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
