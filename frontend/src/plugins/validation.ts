import Vue from "vue";
import {ValidationProvider, ValidationObserver, extend} from "vee-validate";

extend('required', {
  validate(value) {
    return {
      required: true,
      valid: ['', null, undefined].indexOf(value) === -1
    };
  },
  message: 'Обязательное поле',
  computesRequired: true
});

extend('email', {
  validate(value: string) {
    return {
      valid: value.length >= 3 && value.indexOf('@') !== -1
    };
  },
  message: 'Неверный формат почты'
});

extend('integer', {
  validate(value: string) {
    return {
      valid: /^\d+$/.test(value)
    };
  },
  message: 'Неверный формат числа'
});

extend('min', {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  validate(value: string, {min}) {
    const number = Number(value);
    return {
      valid: Number.isNaN(number) ? value.length >= min : number >= min,
    };
  },
  params: ['min'],
  message: 'Минимум: {min}'
});

extend('unique', {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  validate(value: string, {items}) {
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    const a = items.map(item => item.toUpperCase())

    return {
      valid: a.indexOf(value.toUpperCase()) === -1,
    };
  },
  params: ['items'],
  message: 'Уже используется'
});


extend('secret', {
  validate: value => value === 'example',
  message: 'This is not the magic word'
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
