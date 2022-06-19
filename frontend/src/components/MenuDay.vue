<template>
  <div class="md" :class="{current: currentDay}">
    <div class="md__row" v-for="(meal, index) of day.meals" :key="index">
      <div class="md__row-day">
        <template v-if="index === centerIndex">
          {{ dayName }}
        </template>
      </div>
      <div class="md__row-meal">
        {{ meal.name }}
      </div>
      <div class="md__row-dish">
        <div v-if="editMealId !== meal.id" @click="$router.push('/dishes/' + meal.dish.id)">
          {{ meal.dish.name }}
        </div>
        <v-autocomplete
          v-else
          v-model="dishSelectedId"
          :class="'md__row-dish-edit-' + _uid"
          item-text="name"
          item-value="id"
          persistent-placeholder
          :loading="dishLoading"
          :items="dishes"
          :search-input.sync="dishSearch"
          autofocus
          @focus="ocRegister('md__row-dish-edit-' + _uid, () => {
            editDish();
          }, true)
          "
        />
      </div>
      <div class="md__row-actions">
        <v-tooltip
          v-if="editMealId !== meal.id"
          bottom
          open-delay="300"
        >
          <template #activator="{ on, attrs }">
            <v-btn icon v-on="on" v-bind="attrs" @click="editMealId = meal.id;">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          Изменить блюдо
        </v-tooltip>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop, Watch} from 'vue-property-decorator'
import {Dish, DishIndex} from "@/models/Dish";
import {Image} from "@/models/Image";
import DishRepository from "@/repositories/DishRepository";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";

export type Day = {
  number: number,
  meals: {
    id: number,
    name: string,
    dish: Dish & {
      images: Image[]
    }
  }[]
}

@Component({})
export default class MenuDay extends mixins(OutsideClickMixin) {
  @Prop({required: true}) readonly day!: Day;
  @Prop({required: true}) readonly dayName!: string;
  @Prop({default: false}) readonly currentDay!: boolean;

  private editMealId = 0;
  private dishSelectedId = 0;
  private dishLoading = false;
  private dishes: DishIndex[] = [];
  private dishSearch = '';

  get centerIndex(): number {
    const len = this.day.meals.length;

    if (len % 2 === 0) {
      return len / 2 - 1;
    }

    return (len - 1) / 2;
  }

  delay(n: number): Promise<void> {
    return new Promise(function (resolve) {
      setTimeout(resolve, n);
    });
  }

  private editDish(): void {
    if (this.dishSelectedId !== 0) {
      this.updatedEvent();
    }

    this.editMealId = 0;
    this.dishSelectedId = 0;
    this.dishSearch = '';
  }

  @Emit('updated')
  updatedEvent(): {
    meal_id: number,
    dish_id: number,
    day: number
  } {
    return {
      meal_id: this.editMealId,
      dish_id: this.dishSelectedId,
      day: this.day.number
    }
  }

  @Watch('dishSearch')
  async loadDishes(): Promise<void> {
    this.dishLoading = true;
    const filter = this.dishSearch;

    await this.delay(200);
    if (filter !== this.dishSearch) return;

    const {data} = await DishRepository.paginate(1, undefined, filter);

    if (filter !== this.dishSearch) return;

    this.dishes = data.data;
    this.dishLoading = false;
  }
}
</script>

<style scoped lang="scss">
.md {
  background: #FFFFFF;
  border: 1px solid #00000033;
  border-radius: 25px;
  padding: 15px 0;

  &.current {
    background: #FFEDD3;
  }

  .md__row {
    display: flex;
    align-items: center;
    border-bottom: 0.7px solid #00000026;
    padding: 0 30px;
    height: 50px;

    &:last-child {
      border-bottom: none;
    }

    &:hover {
      .md__row-actions {
        display: flex;
      }
    }

    .md__row-day {
      font-size: 18px;
      font-weight: 500;

      width: 7%;
    }

    .md__row-meal {
      width: 15%;
    }

    .md__row-dish {
      cursor: pointer;
      flex-grow: 1;
    }

    .md__row-actions {
      display: none;
    }
  }
}
</style>
