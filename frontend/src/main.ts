import Vue from 'vue'
import './plugins/axios'
import './plugins/validation'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import moment from "moment";
import {AxiosError} from "axios";

moment.locale('ru');

Vue.config.productionTip = false
Vue.config.errorHandler = (err, vm, info) => {
  const axiosError = err as AxiosError;

  if (axiosError.response) {
    switch (axiosError.response.status) {
      case 500:
        store.commit('notify', {
          type: 'error',
          text: 'Произошла ошибка на стороне сервера. Попробуйте ещё раз позднее'
        });
        return;
      case 422:
        store.commit('notify', {
          type: 'warning',
          text: 'Ваш запрос не прошёл проверку на сервере'
        });
        return;
    }
  }


  store.commit('notify', {
    type: 'error',
    text: 'Произошла ошибка'
  })

  console.error(err, vm, info);
}


new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')

