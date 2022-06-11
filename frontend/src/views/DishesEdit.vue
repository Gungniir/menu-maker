<template>
  <div class="dish-edit">
    <div v-if="dish" class="dish-edit__container">
      <div class="dish-edit__left">
        <DishEditImages
          class="dish-edit__images-container"
          :images="dish.images"
          :selected-image-id="selectedImageId"
          @store-image="storeImage"
          @detach-image="detachImage"
        />
        <DishEditRecipe
          class="dish-edit__recipe"
          :recipe_items="dish.recipe_items"
          @destroy-recipe-item="destroyRecipeItem"
          @store-recipe-item="storeRecipeItem"
          @update-recipe-item="updateRecipeItem"
        />
      </div>
      <div class="dish-edit__right">
        <div class="dish-edit__name-container">
          <template v-if="!editNameShow">
            <div
              class="dish-edit__name"
              :title="dish.name"
              @click="editNameShow = true; $nextTick(() => $refs.editNameInput.focus())"
            >
              {{ dish.name }}
            </div>
            <div class="dish-edit__name-actions">
              <v-tooltip
                bottom
                open-delay="300"
              >
                <template #activator="{ on, attrs }">
                  <v-btn icon v-on="on" v-bind="attrs"
                         @click="editNameShow = true; $nextTick(() => $refs.editNameInput.focus())">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                Редактировать
              </v-tooltip>
            </div>
          </template>
          <div v-else class="dish-edit__name-input">
            <v-text-field
              v-model="dish.name"
              ref="editNameInput"
              label="Название"
              @focus="ocRegister('dish-edit__name-input', () => {
                editNameShow = false;
                updateDish();
              })"
              @keyup.enter.esc="
                ocDrop('dish-edit__name-input');
                editNameShow = false;
                updateDish();
              "
            />
          </div>
        </div>
        <DishEditIngredients
          class="dish-edit__ingredients-container"
          :ingredients="dish.ingredients"
          @add-ingredient="addIngredient"
          @destroy-ingredient="destroyIngredient"
        />
        <div class="dish-edit__cooking-time-container">
          <div class="dish-edit__cooking-time-header dish-edit__h2">
            Время приготовления:
          </div>
          <div class="dish-edit__cooking-time">

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Prop} from 'vue-property-decorator'
import {DishShow} from "@/models/Dish";
import DishRepository from "@/repositories/DishRepository";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import ImageRepository from "@/repositories/ImageRepository";
import DishEditImages from "@/components/DishEditImages.vue";
import DishEditRecipe from "@/components/DishEditRecipe.vue";
import DishEditIngredients from "@/components/DishEditIngredients.vue";

@Component({
  components: {DishEditIngredients, DishEditRecipe, DishEditImages}
})
export default class DishesEdit extends mixins(OutsideClickMixin) {
  private dish: DishShow | null = null;

  private selectedImageId = 0;

  private editNameShow = false;

  mounted(): void {
    this.loadDish();
  }

  async loadDish(): Promise<void> {
    const {data} = await DishRepository.show(this.dishId);

    this.dish = data;
  }

  async updateDish(): Promise<void> {
    if (this.dish === null) {
      return;
    }

    await DishRepository.update(this.dishId, this.dish);
  }

  async updateRecipeItem({id, value}: {id: number, value: string}): Promise<void> {
    const {data} = await DishRepository.updateRecipe(this.dishId, id, value);

    this.dish = data;
  }

  async storeRecipeItem(value: string): Promise<void> {
    const {data} = await DishRepository.storeRecipe(this.dishId, value);

    this.dish = data;
  }

  async addIngredient({id, amount}: {id: number, amount: number}): Promise<void> {
    const {data} = await DishRepository.storeIngredient(this.dishId, id, amount);

    this.dish = data;
  }

  async destroyIngredient(ingredient_id: number): Promise<void> {
    const {data} = await DishRepository.destroyIngredient(this.dishId, ingredient_id);

    this.dish = data;
  }

  async destroyRecipeItem(recipe_item_id: number): Promise<void> {
    const {data} = await DishRepository.destroyRecipe(this.dishId, recipe_item_id);

    this.dish = data;
  }

  async storeImage(file: File): Promise<void> {
    const {data: imageData} = await ImageRepository.store('Image for dish ' + this.dishId, file);
    const {data: dish} = await DishRepository.attachImage(this.dishId, imageData.id);

    this.dish = dish;
  }

  async detachImage(id: number): Promise<void> {
    const {data: dish} = await DishRepository.detachImage(this.dishId, id);

    this.selectedImageId = 0;
    this.dish = dish;
  }

  @Prop({required: true}) readonly dishId!: number;
}
</script>

<style scoped lang="scss">
.dish-edit {
  width: 100%;

  .dish-edit__container {
    width: 100%;
    padding: 70px;
    display: flex;

    .dish-edit__left {
      width: 55%;
      padding-right: 30px;

      .dish-edit__images-container {
        padding-bottom: 40px;
      }
    }

    .dish-edit__right {
      width: 45%;

      .dish-edit__name-container {
        position: relative;

        .dish-edit__name {
          margin-bottom: 24px;

          overflow: hidden;
          text-overflow: ellipsis;
          font-size: 36px;
          font-weight: 400;
          line-height: 44px;
          letter-spacing: 0;
          text-align: center;
          cursor: pointer;
          border-radius: 10px;
          transition: background-color cubic-bezier(0.25, 0.1, 0.25, 1) 300ms;
        }

        &:hover {
          .dish-edit__name-actions {
            opacity: 1;
          }

          .dish-edit__name {
            background: rgba(0, 0, 0, 0.1);
          }
        }

        .dish-edit__name-actions {
          position: absolute;
          top: 0;
          right: 0;
          height: 100%;

          display: flex;
          align-items: center;
          justify-content: center;

          opacity: 0;

          transition: opacity cubic-bezier(0.25, 0.1, 0.25, 1) 300ms;

          aspect-ratio: 1;
        }
      }

      .dish-edit__ingredients-container {
        margin-bottom: 20px;
      }
    }
  }
}
</style>
