import { createRouter, createWebHistory } from 'vue-router'
import { useHistoryStore } from '@/stores/counter'
import HomeView from '@/views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomeView,
      meta: {
        fullPage: true
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import(/* webpackChunkName: "login" */ '@/views/LoginView.vue'),
      meta: {
        fullPage: true,
      }
    },
    // {
    //   path: '/menus',
    //   name: 'Menus',
    //   component: () => import(/* webpackChunkName: "menus" */ '@/views/Menus.vue'),
    //   meta: {
    //     fullPage: false,
    //     withoutBackground: true,
    //     showInMenu: true,
    //     icon: '$menu',
    //   }
    // },
    // {
    //   path: '/dishes',
    //   name: 'Dishes',
    //   component: () => import(/* webpackChunkName: "dishes" */ '@/views/Dishes.vue'),
    //   meta: {
    //     fullPage: false,
    //     withoutBackground: true,
    //     showInMenu: false,
    //     icon: '$dishes',
    //   }
    // },
    // {
    //   path: '/dishes/:dishId(\\d+)/edit',
    //   name: 'DishEdit',
    //   component: () => import(/* webpackChunkName: "dish" */ '@/views/Dish.vue'),
    //   props: route => ({dishId: Number(route.params.dishId)}),
    //   meta: {
    //     fullPage: false,
    //     withoutBackground: false,
    //     showInMenu: false,
    //   }
    // },
    // {
    //   path: '/dishes/:dishId(\\d+)',
    //   name: 'Dish',
    //   component: () => import(/* webpackChunkName: "dish" */ '@/views/Dish.vue'),
    //   props: route => ({dishId: Number(route.params.dishId)}),
    //   meta: {
    //     fullPage: false,
    //     withoutBackground: false,
    //     showInMenu: false,
    //   }
    // },
    {
      path: '/categories',
      name: 'Categories',
      component: () => import(/* webpackChunkName: "categories" */ '@/views/CategoriesView.vue'),
      meta: {
        fullPage: false,
        withoutBackground: true,
        showInMenu: true,
        icon: 'custom:DishesIcon',
      }
    },
    // {
    //   path: '/products',
    //   name: 'Products',
    //   component: () => import(/* webpackChunkName: "products" */ '@/views/Products.vue'),
    //   meta: {
    //     fullPage: false,
    //     showInMenu: true,
    //     icon: '$products',
    //   }
    // },
    // {
    //   path: '/ingredients',
    //   name: 'Ingredients',
    //   component: () => import(/* webpackChunkName: "ingredients" */ '@/views/Ingredients.vue'),
    //   meta: {
    //     fullPage: false,
    //     showInMenu: true,
    //     icon: '$ingredients',
    //   }
    // },
    // {
    //   path: '/categories/:categoryId(\\d+)',
    //   name: 'Category',
    //   component: () => import(/* webpackChunkName: "dishes" */ '@/views/Dishes.vue'),
    //   props: route => ({categoryId: Number(route.params.categoryId)}),
    //   meta: {
    //     fullPage: false,
    //     withoutBackground: true,
    //     showInMenu: false,
    //   }
    // },
  ]
})

export default {
  createAppRouter: () => {
    const { history } = useHistoryStore();

    router.afterEach((to, from) => {
      history.push(from);
    })

    return router;
  }
}
