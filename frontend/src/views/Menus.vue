<template>
  <div class="menu">
    <div class="menu__wrapper">
      <v-row>
        <v-col class="pt-5 d-flex justify-center">
          <v-menu offset-y bottom>
            <template #activator="{ on, attrs }">
              <v-text-field
                v-on="on"
                v-bind="attrs"
                class="flex-grow-0 hide-messages"
                label="Неделя"
                outlined
                color="primary"
                background-color="white"
                readonly
                :value="weekRange"
              />
            </template>
            <v-date-picker v-model="date" locale="ru" first-day-of-week="1"/>
          </v-menu>
        </v-col>
      </v-row>
      <template v-if="menuForShow">
        <menu-day class="mb-4" v-for="(day, index) of menuForShow.days" :key="index" :day="day" :day-name="days[index]" />
      </template>
    </div>
    <v-tooltip
      bottom
      open-delay="300"
    >
      <template #activator="{ on, attrs }">
        <v-btn
          color="primary"
          fab
          fixed
          bottom
          right
          style="bottom: 50px; right: calc(13vw + 25px)"
          v-on="on"
          v-bind="attrs"
          @click="showAddDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Создать меню
    </v-tooltip>
    <menu-add-dialog v-model="showAddDialog" :start-date="firstDayOfWeek"/>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator'
import MenuAddDialog from "@/components/MenuAddDialog.vue";
import moment from "moment";
import MenuRepository from "@/repositories/MenuRepository";
import {MenuShow} from "@/models/Menu";
import MenuDay, {Day} from "@/components/MenuDay.vue";

type MenuForShow = {
  days: Day[]
}

@Component({
  components: {MenuDay, MenuAddDialog}
})
export default class Menus extends Vue {
  private showAddDialog = false;
  private date = '';
  private menu: MenuShow | null = null;
  private days = ['Пт', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

  get firstDayOfWeek(): string {
    if (!this.date) {
      return moment().startOf('week').format('YYYY-MM-DD');
    }

    return moment(this.date).startOf('week').format('YYYY-MM-DD');
  }

  get weekRange(): string {
    if (!this.date) {
      return moment().startOf('week').format('DD') + ' - ' +
        moment().endOf('week').format('DD MMMM YYYY');
    }

    return moment(this.date).startOf('week').format('DD') + ' - ' +
      moment(this.date).endOf('week').format('DD MMMM YYYY');
  }

  get menuForShow(): MenuForShow | null {
    if (!this.menu) {
      return null;
    }

    const result: MenuForShow = {
      days: []
    };

    for (let i = 0; i < 7; i++) {
      result.days.push({
        meals: []
      })
    }

    for (const meal of this.menu.meals) {
      for (const item of meal.items) {
        result.days[item.day].meals.push({
          name: meal.name,
          dish: item.dish
        })
      }
    }

    return result;
  }

  mounted(): void {
    this.loadMenu()
  }


  @Watch('firstDayOfWeek')
  async loadMenu(): Promise<void> {
    const {data} = await MenuRepository.showForDate(this.firstDayOfWeek);

    this.menu = data;
  }
}
</script>

<style scoped lang="scss">
.menu {
  position: relative;
  height: 100%;
  overflow: hidden;
}
</style>
