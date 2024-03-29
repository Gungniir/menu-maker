<template>
  <dialog-card title="Отметьте одинаковым цветом те приемы пищи, в которые блюда будут повторяться" v-model="opened">
    <table class="scheme__table">
      <thead>
        <tr>
          <th />
          <th v-for="day of days" :key="day">{{ day }}</th>
          <th />
        </tr>
      </thead>
      <tbody>
        <tr v-for="(meal, index) of meals" :key="meal">
          <td>{{ meal }}</td>
          <td v-for="(item, i) of items[index]" :key="i" @click="onCellClick(index, i)">
            <div class="circle" :style="`background:${item.color}`" :class="{filled: item.id}" />
          </td>
          <td>
            <v-btn icon>
              <v-icon @click="deleteMeal(index)">mdi-delete</v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="scheme__add-meal-container">
      <validation-observer v-show="showAddMealInput" slim v-slot="{ invalid }">
        <div class="scheme__add-meal-input">
          <validation-provider slim rules="required">
            <v-text-field
              v-model="addMealValue"
              ref="addMealValueInput"
              label="Название приема пищи"
              @focus="ocRegister('scheme__add-meal-input', () => {
                showAddMealInput = false;
                addMealValue = '';
              })"
            />
          </validation-provider>
          <v-btn
            class="ml-4"
            color="primary"
            :disabled="invalid"
            @click="ocDrop('scheme__add-meal-input'); addMeal();"
          >
            Добавить
          </v-btn>
        </div>
      </validation-observer>
      <div v-show="!showAddMealInput" class="scheme__add-meal-button">
        <v-btn icon @click="showAddMealInput = true; $nextTick(() => $refs.addMealValueInput.focus())">
          <v-icon>$add</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="scheme__colors mt-4">
      <div
        v-for="group of groups"
        v-ripple
        class="scheme__color mx-3"
        :key="group.id"
        :style="`background:${group.color}`"
        @click="selectedGroup !== group ? selectedGroup = group : selectedGroup = null"
      >
        <v-icon v-if="selectedGroup === group">mdi-check</v-icon>
      </div>
    </div>
    <validation-observer tag="div" v-slot="{ invalid }">
      <div class="mt-4">
        <validation-provider slim rules="required">
          <v-text-field :loading="loading" class="hide-messages" v-model="name" outlined label="Название" persistent-placeholder />
        </validation-provider>
      </div>
      <div class="mt-4">
        <v-btn block color="primary" large :disabled="invalid || loading" @click="onContinueClick">Применить</v-btn>
      </div>
    </validation-observer>
  </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import {MenuSchemeEntity, MenuSchemeShow} from "@/models/MenuScheme";
import MenuSchemeRepository from "@/repositories/MenuSchemeRepository";
import {MenuSchemeMeal} from "@/models/MenuSchemeMeal";

@Component({
  components: {DialogCard}
})
export default class MenuSchemeAddEditDialog extends mixins(OutsideClickMixin) {
  @Prop({default: false}) value!: boolean;
  @Prop({default: 0}) schemeId!: number;

  private opened = false;
  private loading = false;
  private showAddMealInput = false;
  private addMealValue = '';
  private name = '';
  private selectedGroup = null as {
    id: number,
    color: string
  } | null
  private meals = ['Завтрак', 'Обед', 'Ужин'];
  private days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
  private items = [[]] as {
    id: number,
    color: string
  }[][];
  private groups = [
    {
      id: 1,
      color: '#CAFCDE'
    },
    {
      id: 2,
      color: '#FCE8CA'
    },
    {
      id: 3,
      color: '#E5E5E5'
    },
    {
      id: 4,
      color: '#FCCACA'
    },
    {
      id: 5,
      color: '#D8EFFF'
    },
    {
      id: 6,
      color: '#FBFCCA'
    },
    {
      id: 7,
      color: '#F9F9F9'
    },
    {
      id: 8,
      color: '#ECCAFC'
    },
    {
      id: 9,
      color: '#CBFCCA'
    },
    {
      id: 10,
      color: '#7e93fd'
    },
    {
      id: 11,
      color: '#fcc66b'
    },
    {
      id: 12,
      color: '#da7474'
    },
    {
      id: 13,
      color: '#ff3f3f'
    },
    {
      id: 14,
      color: '#6ec3fd'
    },
    {
      id: 15,
      color: '#faff5e'
    },
    {
      id: 16,
      color: '#3d3d3d'
    },
    {
      id: 17,
      color: '#77269f'
    },
    {
      id: 18,
      color: '#0b5007'
    },
  ] as {
    id: number,
    color: string
  }[];

  mounted(): void {
    this.opened = this.value;
    this.reset();
  }

  private onCellClick(mealIndex: number, dayIndex: number): void {
    this.$set(this.items[mealIndex], dayIndex, this.selectedGroup ? this.selectedGroup : {
      id: 0,
      color: '#FFFFFF',
    });
  }

  private onContinueClick(): void {
    if (this.schemeId) {
      this.updateScheme();
    } else {
      this.storeScheme();
    }
    this.opened = false;
  }

  private addMeal(): void {
    this.meals.push(this.addMealValue);
    const items = [];

    for (let i = 0; i < this.days.length; i++) {
      items.push({
        id: 0,
        color: '#FFFFFF'
      });
    }

    this.items.push(items);

    this.addMealValue = '';
    this.showAddMealInput = false;
  }

  private deleteMeal(index: number): void {
    this.meals.splice(index, 1);
    this.items.splice(index, 1);
  }

  private getSchemeEntity(): MenuSchemeEntity {
    const groups = [];

    for (const group of this.groups) {
      const g = [];
      for (const mealsKey in this.meals) {
        for (const daysKey in this.days) {
          if (this.items[mealsKey][daysKey].id === group.id) {
            g.push({
              meal: this.meals[mealsKey],
              day: Number(daysKey),
            })
          }
        }
      }

      if (g.length > 0) {
        groups.push(g);
      }
    }


    return {
      name: this.name,
      meals: this.meals,
      groups: groups,
    }
  }

  async storeScheme(): Promise<void> {
    this.loading = true;
    const {data} = await MenuSchemeRepository.store(this.getSchemeEntity());
    this.createdEvent(data);
    this.loading = false;
  }

  async updateScheme(): Promise<void> {
    this.loading = true;
    const {data} = await MenuSchemeRepository.update(this.schemeId, this.getSchemeEntity());
    this.updatedEvent(data);
    this.loading = false;
  }

  async loadScheme(): Promise<void> {
    this.loading = true;
    const {data} = await MenuSchemeRepository.show(this.schemeId);

    this.name = data.name;
    this.meals = data.meals.map(meal => meal.name);
    this.resetItems();

    for (let i = 0; i < this.meals.length; i++) {
      const meal = data.meals.find(meal => meal.name === this.meals[i]) as MenuSchemeMeal;

      for (let j = 0; j < this.days.length; j++) {
        for (const groupIndex in data.groups) {
          for (const item of data.groups[groupIndex].items) {
            if (item.day === j && item.meal_id === meal.id) {
              this.$set(this.items[i], j, this.groups[groupIndex]);
            }
          }
        }
      }
    }
    this.loading = false;
  }

  private reset(): void {
    this.meals = ['Завтрак', 'Обед', 'Ужин'];
    this.selectedGroup = null;
    this.name = '';
    this.resetItems();
  }

  private resetItems(): void {
    this.items = [];
    for (let i = 0; i < this.meals.length; i++) {
      this.items.push([]);
      for (let j = 0; j < this.days.length; j++) {
        this.items[i].push({
          id: 0,
          color: '#FFFFFF'
        });
      }
    }
  }

  @Emit('created')
  createdEvent(scheme: MenuSchemeShow): MenuSchemeShow {
    return scheme;
  }

  @Emit('updated')
  updatedEvent(scheme: MenuSchemeShow): MenuSchemeShow {
    return scheme;
  }

  @Watch('opened')
  onOpenedChanged(value: boolean): void {
    this.$emit('input', value);
  }

  @Watch('value')
  onValueChanged(value: boolean): void {
    this.opened = value;

    if (value) {
      this.reset();

      if (this.schemeId) {
        this.loadScheme();
      }
    }
  }
}
</script>

<style scoped lang="scss">
.scheme__table {
  width: 100%;
  border-collapse: collapse;

  th, td {
    padding: 8px 10px;
    border: 1px solid rgba(0, 0, 0, 0.2);

    font-size: 16px;
    font-weight: 400;
    line-height: 20px;

    .circle {
      height: 25px;
      width: 25px;
      border-radius: 50%;

      &.filled {
        border: 1px solid rgba(0, 0, 0, 0.2);
      }
    }
  }
}

.scheme__add-meal-container {
  .scheme__add-meal-input {
    display: flex;
    align-items: center;
  }
}

.scheme__colors {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;

  .scheme__color {
    height: 50px;
    width: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.2);

    cursor: pointer;
  }
}
</style>
