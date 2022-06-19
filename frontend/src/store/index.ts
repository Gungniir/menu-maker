import Vue from 'vue'
import Vuex from 'vuex'
import {Route} from "vue-router";

Vue.use(Vuex)

type NotifyPayload = {
  type: 'success' | 'info' | 'error' | 'warning',
  text: string
}

export default new Vuex.Store({
  state: {
    notificationType: 'info',
    notificationText: 'Hello world!',
    notificationShowed: false,
    history: [] as Route[],
  },
  mutations: {
    notify(state, payload: NotifyPayload) {
      state.notificationShowed = false;
      state.notificationType = payload.type;
      state.notificationText = payload.text;
      state.notificationShowed = true;
    }
  },
  actions: {
  },
  modules: {
  }
})
