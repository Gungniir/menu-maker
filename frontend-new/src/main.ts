import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import appRouter from './router'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { mdi } from 'vuetify/iconsets/mdi'
import { customSVGs } from '@/components/icons/customIcons'

const app = createApp(App)
const pinia = createPinia();
const vuetify = createVuetify({
  components,
  directives,
  theme: {
    themes: {
      light: {
        dark: false,

        colors: {

          primary: '#FF7043',
          'app-background': '#F0F0F0',
          text: '#3C3C3C',
          background: '#FFFFFF',

          icon: '#7D8488',
        },
      }
    },
    options: {
      customProperties: true
    }
  },
  icons: {
    defaultSet: "mdi",
    sets: {
      mdi,
      custom: customSVGs,
    },
  },
})

app.use(pinia)
app.use(appRouter.createAppRouter())
app.use(vuetify)

app.mount('#app')
