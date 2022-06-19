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
      <template v-if="loading">
        <v-skeleton-loader v-for="day in 7" :key="day" class="fluid mb-4" type="image" height="200" style="border-radius: 25px"/>
      </template>
      <template v-else>
        <template v-if="menuForShow">
          <menu-day class="mb-4" v-for="(day, index) of menuForShow.days" :key="index" :day="day" :day-name="days[index]" :current-day="index === currentWeekDay" />
        </template>
        <div v-else class="d-flex align-center flex-column menu__not-found">
          <v-img class="flex-grow-0" height="400" width="400" :src="require('@/assets/woman.svg')"/>
          Создай своё неповторимое меню!
        </div>
      </template>
    </div>
    <v-speed-dial
      v-if="!loading && menu"
      v-model="speedDeal"
      fixed
      bottom
      right
      direction="top"
      transition="slide-y-reverse-transition"
      style="bottom: 50px; right: calc(13vw + 25px)"
    >
      <template #activator>
        <v-btn
          v-model="speedDeal"
          fab
          color="primary"
        >
          <v-icon color="white">mdi-dots-horizontal</v-icon>
        </v-btn>
      </template>
      <v-tooltip
        left
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn
            v-on="on"
            v-bind="attrs"
            fab
            small
            color="primary"
            @click="showDeleteDialog = true"
          >
            <v-icon color="white">mdi-delete</v-icon>
          </v-btn>
        </template>
        Удалить меню
      </v-tooltip>
      <v-tooltip
        left
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn
            v-on="on"
            v-bind="attrs"
            fab
            small
            color="primary"
            @click="showAddDialog = true"
          >
            <v-icon color="white">mdi-cart-outline</v-icon>
          </v-btn>
        </template>
        Сформировать список продуктов
      </v-tooltip>
    </v-speed-dial>
    <v-tooltip
      v-else
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
          :loading="loading"
          v-on="on"
          v-bind="attrs"
          @click="showAddDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Создать меню
    </v-tooltip>
    <menu-add-dialog v-model="showAddDialog" :start-date="firstDayOfWeek" @created="menu = $event"/>
    <dialog-confirm v-model="showDeleteDialog" title="Удалить меню" text="Вы действительно хотите удалить меню?" @confirm="destroyMenu"/>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator'
import MenuAddDialog from "@/components/MenuAddDialog.vue";
import moment from "moment";
import MenuRepository from "@/repositories/MenuRepository";
import {MenuShow} from "@/models/Menu";
import MenuDay, {Day} from "@/components/MenuDay.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";

type MenuForShow = {
  days: Day[]
}

@Component({
  components: {DialogConfirm, MenuDay, MenuAddDialog}
})
export default class Menus extends Vue {
  private speedDeal = false;
  private showAddDialog = false;
  private showDeleteDialog = false;
  private date = '';
  private menu: MenuShow | null = null;
  private days = ['Пт', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
  private loading = true;

  get currentWeekDay(): number {
    return moment().startOf('week').format('YYYY-MM-DD') === this.firstDayOfWeek ? moment().weekday() : -1;
  }

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
    this.loading = true;

    try {
      const {data} = await MenuRepository.showForDate(this.firstDayOfWeek);
      this.menu = data;

    } catch (e) {
      this.menu = null;

      if (e.response.status !== 404) {
        throw e;
      }
    }

    this.loading = false;
  }

  async destroyMenu(): Promise<void> {
    if (!this.menu) {
      return;
    }

    await MenuRepository.destroy(this.menu.id);

    this.menu = null;
  }
}
</script>

<style scoped lang="scss">
.menu {
  position: relative;
  height: 100%;
  overflow: hidden;

  .menu__not-found {
    font-size: 30px;
    font-weight: 400;
    letter-spacing: 0.0125em;
  }
}
</style>
