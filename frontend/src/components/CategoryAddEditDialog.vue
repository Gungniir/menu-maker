<template>
  <dialog-card
    ref="addDialog"
    v-model="opened"
    v-slot="{ invalid }"
    with-observer
    :title="(categoryId ? 'Изменить' : 'Добавить') + ' категорию'"
  >
    <v-row no-gutters>
      <v-col>
        <validation-provider slim vid="name" name="название" rules="required" v-slot="{ errors }">
          <v-text-field
            v-model="categoryName"
            outlined color="primary"
            label="Название"
            persistent-placeholder
            :error-messages="errors"
            :loading="categoryLoading"
          />
        </validation-provider>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col>
        <v-autocomplete
          v-model="dishSelectedId"
          item-text="name"
          item-value="id"
          label="Добавить блюдо в категорию"
          persistent-placeholder
          outlined
          :loading="dishLoading"
          :items="dishes"
          :search-input.sync="dishSearch"
        />
      </v-col>
    </v-row>
    <v-row v-if="dishesSelected.length > 0" no-gutters class="mb-6">
      <v-col>
        Блюда в категории:
        <ul>
          <li v-for="(dish, index) of dishesSelected" :key="dish.id">
            <div class="d-flex justify-space-between align-center">
              {{ dish.name }}
              <v-btn icon @click="dishesSelected.splice(index, 1)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </div>
          </li>
        </ul>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col class="d-flex justify-end">
        <v-btn color="primary" outlined large @click="opened = false">Отмена</v-btn>
        <v-btn class="ml-4" color="primary" large :disabled="invalid" @click="onSubmit">
          {{categoryId ? 'Изменить' : 'Создать' }}
        </v-btn>
      </v-col>
    </v-row>
  </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import {Dish, DishIndex} from "@/models/Dish";
import CategoryRepository from "@/repositories/CategoryRepository";
import {CategoryShow} from "@/models/Category";
import DishRepository from "@/repositories/DishRepository";


@Component({
  components: { DialogCard }
})
export default class CategoryAddEditDialog extends Vue {
  @Prop({default: false}) value!: boolean;
  @Prop({default: 0}) categoryId!: number;

  private opened = false;

  private categoryLoading = false;

  private categoryName = '';
  private dishes: DishIndex[] = [];
  private dishLoading = false;
  private dishSearch = '';
  private dishSelectedId = 0;
  private dishesSelected: Dish[] = [];

  get dishSelected(): DishIndex | null {
    const dish = this.dishes.find(({id}) => id === this.dishSelectedId);

    return dish ?? null;
  }

  private reset(): void {
    this.categoryName = '';
    this.dishLoading = false;
    this.dishSearch = '';
    this.dishSelectedId = 0;
    this.dishesSelected = [];
  }

  onSubmit(): void {
    if (this.categoryId) {
      this.updateCategory();
    } else {
      this.storeCategory();
    }
  }

  async storeCategory(): Promise<void> {
    const {data} = await CategoryRepository.store({
      name: this.categoryName,
      dishes: this.dishesSelected.map(dish => dish.id),
    });

    this.opened = false;

    this.createdEvent(data);
  }

  async updateCategory(): Promise<void> {
    const {data} = await CategoryRepository.update(this.categoryId, {
      name: this.categoryName,
      dishes: this.dishesSelected.map(dish => dish.id),
    });

    this.opened = false;

    this.updatedEvent(data);
  }

  @Watch('dishSearch')
  async loadDishes(): Promise<void> {
    if (this.dishSelected?.name === this.dishSearch) return;

    this.dishLoading = true;
    const search = this.dishSearch;

    await this.delay(200);
    if (search !== this.dishSearch) return;

    const {data} = await DishRepository.paginate(1, undefined, search);

    if (search !== this.dishSearch) return;

    this.dishes = data.data;
    this.dishLoading = false;
  }

  @Watch('dishSelected')
  addDish(): void {
    if (!this.dishSelected) return;

    this.dishesSelected.push(this.dishSelected);
    this.$nextTick(() => {
      this.dishSelectedId = 0;
      this.dishSearch = '';
    })
  }

  async loadCategory(): Promise<void> {
    this.categoryLoading = true;

    const {data} = await CategoryRepository.show(this.categoryId);

    this.categoryName = data.name;
    this.dishesSelected = data.dishes;

    this.categoryLoading = false;
  }

  delay(n: number): Promise<void> {
    return new Promise(function(resolve){
      setTimeout(resolve,n);
    });
  }

  @Emit('created')
  createdEvent(category: CategoryShow): CategoryShow {
    return category;
  }

  @Emit('updated')
  updatedEvent(category: CategoryShow): CategoryShow {
    return category;
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

      if (this.categoryId) {
        this.loadCategory();
      }
    }
  }
}
</script>

<style scoped lang="scss">

</style>
