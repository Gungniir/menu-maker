<script setup lang="ts">
import { RouterView, useRoute, useRouter } from 'vue-router'
import { computed } from 'vue'
import AppPage from '@/components/AppPage.vue'
import AppNotification from '@/components/AppNotification.vue'

const router = useRouter()
const route = useRoute()

const withPage = computed(() => !route.meta?.fullPage)
const withBackground = computed(() => !route.meta?.withoutBackground)

const pages = computed(() => router.getRoutes().filter(({ meta }) => meta?.showInMenu).map(r => ({
  link: r.path,
  selected: route.path === r.path,
  icon: r.meta.icon ?? 'mdi-close',
})))
</script>

<template>
  <v-app id="app">
    <v-main>
      <AppPage v-if="withPage" :pages="pages" :without-background="!withBackground">
        <router-view />
      </AppPage>
      <router-view v-else />
    </v-main>
    <AppNotification />
  </v-app>
</template>

<style scoped lang="scss">
#app {
  color: var(--v-text-base);
  background: var(--v-app-background-base);
  font-family: Montserrat, serif;

  .v-icon {
    color: var(--v-icon-base);
  }

  .v-input.min-height {
    .v-input__slot {
      min-height: auto !important;
    }
  }

  .hide-messages {
    .v-text-field__details {
      display: none;
    }
  }

  .v-skeleton-loader.fluid > div {
    height: 100%;
    width: 100%;
  }
}
</style>
