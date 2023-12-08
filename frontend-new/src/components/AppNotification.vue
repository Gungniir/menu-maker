<script setup lang="ts">
import { useNotifierStore } from '@/stores/notifier'

const { currentText, currentType, notificationShowed } = useNotifierStore()
</script>

<template>
  <v-alert
    v-model="notificationShowed"
    class="notification"
    border="start"
    transition="slide-x-reverse-transition"
    elevation="5"
    colored-border
    :type="currentType"
  >
    {{ currentText }}
  </v-alert>
</template>

<style scoped lang="scss">
.notification {
  z-index: 1000;
  position: fixed;
  right: 15px;
  bottom: 15px;
  max-width: 30vw;
  word-break: break-word;
}
</style>
