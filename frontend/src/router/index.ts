import Vue from 'vue'
import VueRouter, { RouteConfig } from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes: Array<RouteConfig> = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      fullPage: true
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "login" */ '../views/Login.vue'),
    meta: {
      fullPage: true,
    }
  },
  {
    path: '/menus',
    name: 'Menus',
    component: () => import(/* webpackChunkName: "menus" */ '../views/Menus.vue'),
    meta: {
      fullPage: false,
      showInMenu: true,
      icon: '$menu',
    }
  },
  {
    path: '/dishes',
    name: 'Dishes',
    component: () => import(/* webpackChunkName: "dishes" */ '../views/Dishes.vue'),
    meta: {
      fullPage: false,
      showInMenu: true,
      icon: '$dishes',
    }
  },
  {
    path: '/products',
    name: 'Products',
    component: () => import(/* webpackChunkName: "products" */ '../views/Products.vue'),
    meta: {
      fullPage: false,
      showInMenu: true,
      icon: '$products',
    }
  },
  {
    path: '/ingredients',
    name: 'Ingredients',
    component: () => import(/* webpackChunkName: "ingredients" */ '../views/Ingredients.vue'),
    meta: {
      fullPage: false,
      showInMenu: true,
      icon: '$ingredients',
    }
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
