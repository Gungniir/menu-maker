import { ref } from 'vue'
import { defineStore } from 'pinia'

export type NotificationType = 'success' | 'warning' | 'error'

export const useNotifierStore = defineStore('notifier', () => {
  const currentText = ref('')
  const currentType = ref<NotificationType>('success')
  const notificationShowed = ref(false)
  let notificationTimeout = 0

  const notify = (text: string, type: NotificationType = 'success') => {
    clearTimeout(notificationTimeout)
    notificationTimeout = setTimeout(() => {
      notificationShowed.value = false
    })

    currentText.value = text
    currentType.value = type
    notificationShowed.value = true
  }

  return { currentText, currentType, notificationShowed, notify }
})
