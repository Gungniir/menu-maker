import Vue from 'vue'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'

extend('required', {
  validate (value) {
    return {
      required: true,
      valid: ['', null, undefined].indexOf(value) === -1,
    }
  },
  message: 'Обязательное поле',
  computesRequired: true,
})

extend('email', {
  validate (value: string) {
    return {
      valid: value.length >= 3 && value.indexOf('@') !== -1,
    }
  },
  message: 'Неверный формат почты',
})

extend('integer', {
  validate (value: string) {
    return {
      valid: /^\d+$/.test(value),
    }
  },
  message: 'Неверный формат числа',
})

extend('decimal', {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  validate (value: string, { digits }) {
    const regex = new RegExp(`^\\d+([,.]\\d{1,${digits}})?$`)

    return {
      valid: regex.test(value),
    }
  },
  params: ['digits'],
  message: (_, params) => {
    if (!/^\d+([,.]\d+)?$/.test(params._value_)) {
      return 'Неверный формат числа'
    }

    return `Максимум цифр после запятой: ${params.digits}`
  },
})

extend('min', {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  validate (value: string, { min }) {
    const number = Number(value)
    return {
      valid: Number.isNaN(number) ? value.length >= min : number >= min,
    }
  },
  params: ['min'],
  message: 'Минимум: {min}',
})

extend('unique', {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  validate (value: string, { items, allowed }) {
    if (value === allowed) return true

    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    const a = items.map(item => item.toUpperCase())

    return {
      valid: a.indexOf(value.toUpperCase()) === -1,
    }
  },
  params: ['items', 'allowed'],
  message: 'Уже используется',
})

extend('secret', {
  validate: value => value === 'example',
  message: 'This is not the magic word',
})

Vue.component('ValidationObserver', ValidationObserver)
Vue.component('ValidationProvider', ValidationProvider)
