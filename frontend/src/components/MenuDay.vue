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
      <div class="md__row-dish" @click="$router.push('/dishes/' + meal.dish.id)">
        {{ meal.dish.name }}
      </div>
      <div class="md__row-actions">
        <v-tooltip
          bottom
          open-delay="300"
        >
          <template #activator="{ on, attrs }">
            <v-btn icon v-on="on" v-bind="attrs">
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
import {Component, Prop, Vue} from 'vue-property-decorator'
import {Dish} from "@/models/Dish";
import {Image} from "@/models/Image";

export type Day = {
  meals: {
    name: string,
    dish: Dish & {
      images: Image[]
    }
  }[]
}

@Component({})
export default class MenuDay extends Vue {
  @Prop({required: true}) readonly day!: Day;
  @Prop({required: true}) readonly dayName!: string;
  @Prop({default: false}) readonly currentDay!: boolean;

  get centerIndex(): number {
    const len = this.day.meals.length;

    if (len % 2 === 0) {
      return len / 2 - 1;
    }

    return (len - 1) / 2;
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
