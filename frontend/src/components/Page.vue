<template>
  <div class="page">
    <div class="page__left-panel">
      <div class="left-panel__logo">
        <v-img :src="require('@/assets/logo-2.svg')" />
      </div>
      <div class="left-panel__menu">
        <div
          v-for="page of pages"
          :key="page.link"
          class="menu__item"
          v-ripple
        >
          <v-icon size="60" :color="page.selected ? 'primary' : 'icon'">{{ page.icon }}</v-icon>
        </div>
      </div>
    </div>
    <div class="page__content">
      <slot />
    </div>
    <div class="page__right-panel">
      <div class="right-panel__profile">
        <v-icon size="60" color="primary">mdi-account-circle</v-icon>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {PageMenuItem} from "@/models/PageMenuItem";

@Component({})
export default class Page extends Vue {
  @Prop({default: () => ([])}) readonly pages!: PageMenuItem[]
}
</script>

<style scoped lang="scss">
.page {
  height: 100%;
  overflow: hidden;

  display: flex;

  .page__left-panel {
    max-width: 345px;
    width: 17vw;
    padding: 40px;

    display: flex;
    flex-direction: column;
    align-items: center;

    .left-panel__logo {
      margin-bottom: 40px;
      align-self: stretch;
    }

    .menu__item {
      background: var(--v-background-base);
      width: 130px;
      height: 90px;
      margin-bottom: 30px;

      display: flex;
      justify-content: center;
      align-items: center;

      border: 1px solid rgba(0, 0, 0, 0.2);
      box-shadow: 0 0 1px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
      border-radius: 20px;

      &:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.14), 0 3px 4px rgba(0, 0, 0, 0.12), 0 1px 5px rgba(0, 0, 0, 0.2);
      }

      transition: all cubic-bezier(0.1, 0, 0.1, 1) 300ms;

      cursor: pointer;
    }
  }

  .page__right-panel {
    max-width: 255px;
    width: 13vw;
    padding: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .page__content {
    flex-grow: 1;
    margin: 60px 0;

    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 25px;

    background: var(--v-background-base);
  }
}
</style>
