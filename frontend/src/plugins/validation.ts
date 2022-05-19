import Vue from "vue";
import {ValidationProvider, ValidationObserver, extend} from "vee-validate";

extend('required', {
  validate(value) {
    return {
      required: true,
      valid: ['', null, undefined].indexOf(value) === -1
    };
  },
  message: 'Поле обязательно для заполнения',
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


extend('secret', {
  validate: value => value === 'example',
  message: 'This is not the magic word'
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
