<template>
  <div class="menu">
    <div class="menu__wrapper">

    </div>
    <v-tooltip
      bottom
      open-delay="300"
    >
      <template #activator="{ on, attrs }">
        <v-btn
          color="primary"
          fab
          absolute
          bottom
          right
          style="bottom: 50px; right: 50px"
          v-on="on"
          v-bind="attrs"
          @click="showAddDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Создать меню
    </v-tooltip>
    <menu-add-dialog v-model="showAddDialog"/>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import MenuAddDialog from "@/components/MenuAddDialog.vue";

@Component({
  components: {MenuAddDialog}
})
export default class Menus extends Vue {
  private showAddDialog = true;
}
</script>

<style scoped lang="scss">
.menu {
  position: relative;
  height: 100%;
  overflow: hidden;


  .menu__wrapper {

  }
}
</style>
