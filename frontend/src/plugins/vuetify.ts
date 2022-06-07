import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'
import Menu from "@/icons/Menu.vue";
import Dishes from "@/icons/Dishes.vue";
import Ingredients from "@/icons/Ingredients.vue";
import Products from "@/icons/Products.vue";
import Add from "@/icons/Add.vue";

Vue.use(Vuetify)

export default new Vuetify({
  theme: {
    themes: {
      light: {
        primary: '#FF7043',
        'app-background': '#F0F0F0',
        text: '#3C3C3C',
        background: '#FFFFFF',

        icon: '#7D8488',
      }
    },
    options: {
      customProperties: true
    }
  },
  icons: {
    values: {
      menu: {
        component: Menu
      },
      dishes: {
        component: Dishes
      },
      ingredients: {
        component: Ingredients
      },
      products: {
        component: Products
      },
      add: {
        component: Add
      }
    }
  },
})
