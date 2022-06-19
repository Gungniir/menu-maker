<template>
  <div class="ingredients">
    <div class="ingredients__wrapper">
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
        <div v-for="(typeIngredients, index) of typedIngredients" :key="index" class="ingredients__category">
          <div class="ingredients__category-header">
            {{ typeIngredients.type }}
          </div>
          <ul style="padding-left: 16px;">
            <li v-for="ingredient of typeIngredients.ingredients" :key="ingredient.id" class="ingredients__category-item">
              <div class="ingredients__category-item-container">
                {{ ingredient.name }} - {{ ingredient.amount }} {{ ingredient.unit }}
                <div class="d-flex ingredients__category-item-container-actions">
                  <v-tooltip
                    bottom
                    open-delay="300"
                  >
                    <template #activator="{ on, attrs }">
                      <v-btn icon v-on="on" v-bind="attrs" @click="selectedIngredientId = ingredient.id; showAddDialog = true">
                        <v-icon>mdi-pencil</v-icon>
                      </v-btn>
                    </template>
                    Редактировать
                  </v-tooltip>
                  <v-tooltip
                    bottom
                    open-delay="300"
                  >
                    <template #activator="{ on, attrs }">
                      <v-btn icon v-on="on" v-bind="attrs" @click="selectedIngredientId = ingredient.id; showDeleteDialog = true">
                        <v-icon>mdi-delete</v-icon>
                      </v-btn>
                    </template>
                    Удалить
                  </v-tooltip>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <ingredient-add-edit-dialog v-model="showAddDialog" :ingredient-id="selectedIngredientId" @created="onCreated" @updated="onUpdated" />
      <dialog-confirm v-model="showDeleteDialog" title="Удаление ингредиента" text="Вы действительно хотите удалить этот ингредиент?" @confirm="destroyIngredient"/>
    </div>
    <v-tooltip
      open-delay="300"
      bottom
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
          @click="selectedIngredientId = 0; showAddDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить ингредиент
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import {Ingredient, IngredientShow} from "@/models/Ingredient";
import IngredientRepository from "@/repositories/IngredientRepository";
import Products from "@/views/Products.vue";
import DialogCard from "@/components/DialogCard.vue";
import IngredientAddEditDialog from "@/components/IngredientAddEditDialog.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";

@Component({
  components: {DialogConfirm, IngredientAddEditDialog, DialogCard, Products}
})
export default class Ingredients extends Vue {
  $refs!: {
    addDialog: InstanceType<typeof DialogCard>
  }

  private ingredients: Ingredient[] = [];
  private nextPage = 1;
  private lastPage = 1;
  private mode = 'Мой холодильник';
  private modes = [
    'Мой холодильник', 'Все ингредиенты'
  ];

  private showDeleteDialog = false;
  private showAddDialog = false;
  private selectedIngredientId = 0;

  get typedIngredients(): {
    type: string,
    ingredients: Ingredient[],
  }[] {
    const result: {
      type: string,
      ingredients: Ingredient[],
    }[] = [];

    for (const ingredient of this.filteredIngredients) {
      let index = result.findIndex(a => a.type === (ingredient.type ?? 'Без категории'));

      if (index === -1) {
        index = result.length;

        result.push({
          type: ingredient.type ?? 'Без категории',
          ingredients: []
        })
      }

      result[index].ingredients.push(ingredient);
    }

    result.sort((a, b) => a.type.localeCompare(b.type));

    return result;
  }

  get filteredIngredients(): Ingredient[] {
    if (this.mode === 'Мой холодильник') {
      return this.ingredients.filter(({amount}) => amount > 0);
    }
    return this.ingredients;
  }

  mounted(): void {
    this.loadIngredients();
  }

  private onCreated(item: IngredientShow): void {
    this.ingredients.push(item);
  }

  private onUpdated(item: IngredientShow): void {
    const index = this.ingredients.findIndex(ing => ing.id === item.id);

    this.ingredients.splice(index, 1, item);
  }

  async loadIngredients(): Promise<void> {
    while (this.nextPage <= this.lastPage) {
      await this.fetchIngredients();
    }
  }

  async destroyIngredient(): Promise<void> {
    await IngredientRepository.destroy(this.selectedIngredientId);

    const index = this.ingredients.findIndex(a => a.id === this.selectedIngredientId);

    this.ingredients.splice(index, 1);
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
  position: relative;
  height: 100%;
  overflow: hidden;

  .ingredients__wrapper {
    overflow-y: auto;

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

        .ingredients__category-item-container {
          display: flex;
          justify-content: space-between;

          &:hover {
            .ingredients__category-item-container-actions {
              opacity: 1;
            }
          }

          .ingredients__category-item-container-actions {
            opacity: 0;
          }
        }
      }
    }
  }
}
</style>
