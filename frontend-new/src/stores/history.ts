import { ref } from 'vue'
import { defineStore } from 'pinia'
import type { RouteRecord } from 'vue-router'

export const useHistoryStore = defineStore('counter', () => {
  const history = ref<RouteRecord[]>([]);

  return { history }
})
