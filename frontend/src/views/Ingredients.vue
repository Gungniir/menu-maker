<template>
  <div class="ingredients">
    <div class="ingredients__header">
      <div class="ingredients__header-wrapper">
        <div class="ingredients__header-text">
          {{ mode }}
        </div>
        <v-menu bottom>
          <template #activator="{ on, attrs }">
            <v-btn icon v-on="on" v-bind="attrs" style="margin-top: 4px;">
              <v-icon color="text">mdi-chevron-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item v-for="m of modes" :key="m" @click="mode = m">
              <v-list-item-title>{{ m }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </div>
    <div class="ingredients__container">
      <div class="ingredients__category">
        <div class="ingredients__category-header">
          Без категории
        </div>
        <ul style="padding-left: 16px;">
          <li v-for="ingredient of filteredIngredients" :key="ingredient.id" class="ingredients__category-item">
            {{ ingredient.name }} - {{ ingredient.amount }} {{ ingredient.unit }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {Ingredient} from "@/models/Ingredient";
import IngredientRepository from "@/repositories/IngredientRepository";
import Products from "@/views/Products.vue";

@Component({
  components: {Products}
})
export default class Ingredients extends Vue {
  // noinspection JSMismatchedCollectionQueryUpdate
  private ingredients: Ingredient[] = [];
  private nextPage = 1;
  private lastPage = 1;
  private mode = 'Мой холодильник';
  private modes = [
    'Мой холодильник', 'Все ингредиенты'
  ];

  get filteredIngredients(): Ingredient[] {
    if (this.mode === 'Мой холодильник') {
      return this.ingredients.filter(({amount}) => amount > 0);
    }
    return this.ingredients;
  }

  mounted(): void {
    this.loadIngredients();
  }

  async loadIngredients(): Promise<void> {
    while (this.nextPage <= this.lastPage) {
      await this.fetchIngredients();
    }
  }

  async fetchIngredients(): Promise<void> {
    const {data} = await IngredientRepository.paginate(this.nextPage++);
    this.lastPage = data.last_page;

    this.ingredients.push(...data.data);
  }
}
</script>

<style scoped lang="scss">
.ingredients {
  .ingredients__header {
    padding: 40px;
    display: flex;
    justify-content: center;

    .ingredients__header-wrapper {
      display: flex;
      align-items: center;
      width: fit-content;

      .ingredients__header-text {
        font-size: 36px;
        font-weight: 400;
        line-height: 44px;
        letter-spacing: 0;
        text-align: center;
      }
    }
  }

  .ingredients__container {
    .ingredients__category {
      border-top: 0.7px solid rgba(0, 0, 0, 0.15);
      padding: 15px 70px;

      &:last-child {
        border-bottom: 0.7px solid rgba(0, 0, 0, 0.15);;
      }

      .ingredients__category-header {
        font-size: 20px;
        font-weight: 500;
        line-height: 24px;
        letter-spacing: 0;
        text-align: left;

        margin-bottom: 15px;
      }
    }
  }
}
</style>
