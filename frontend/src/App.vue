<template>
  <v-app id="app">
    <v-main>
      <page v-if="usePage" :pages="pages" :without-background="withoutBackground">
        <router-view />
      </page>
      <router-view v-else />
    </v-main>
  </v-app>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import Page from "@/components/Page.vue";
import {PageMenuItem} from "@/models/PageMenuItem";

@Component({
  components: {Page}
})
export default class App extends Vue {
  get usePage(): boolean {
    return this.$route.meta ? !this.$route.meta.fullPage : false;
  }
  get withoutBackground(): boolean {
    return this.$route.meta ? this.$route.meta.withoutBackground : false;
  }

  get pages(): PageMenuItem[] {
    return this.$router.getRoutes().filter(({meta}) => meta.showInMenu).map(route => ({
      link: route.path,
      selected: this.$route.path === route.path,
      icon: route.meta.icon ?? 'mdi-close'
    }))
  }
}
</script>

<style lang="scss">
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
