<template>
  <div class="dish-edit">
    <div v-if="dish" class="dish-edit__container">
      <div class="dish-edit__left">
        <div class="dish-edit__images-container">
          <div class="dish-edit__image">
            <template v-if="dish.images[0]">
              <v-img :src="selectedImage.url" height="100%" />
              <div class="dish-edit__image-actions">
                <v-tooltip
                  bottom
                  open-delay="300"
                >
                  <template #activator="{ on, attrs }">
                    <v-btn icon v-on="on" v-bind="attrs" @click="detachImage(selectedImage.id)">
                      <v-icon>mdi-minus</v-icon>
                    </v-btn>
                  </template>
                  Удалить
                </v-tooltip>
              </div>
            </template>
            <div v-else class="dish-edit__image-placeholder" />
          </div>
          <div class="dish-edit__images">
            <div v-for="image of dish.images" :key="image.id" class="dish-edit__images-item">
              <v-img :src="image.url" height="100%" @click="selectedImageId = image.id"/>
            </div>
            <div v-if="dish.images.length < 7" class="dish-edit__images-item" v-ripple @click="$refs.imageAddInput.click()">
              <input ref="imageAddInput" type="file" style="display: none" @input="storeImage"/>
              <div class="dish-edit__image-placeholder">
                <v-icon>$add</v-icon>
              </div>
            </div>
          </div>
        </div>
        <div class="dish-edit__recipe">
          <div class="dish-edit__recipe-header dish-edit__h2">
            Способ приготовления:
          </div>

          <div v-for="(recipeItem, index) of dish.recipe_items" :key="recipeItem.id" class="dish-edit__recipe-item">
            <div class="dish-edit__recipe-item-index">
              {{ index + 1 }}
            </div>
            <div class="dish-edit__recipe-item-name">
              {{ recipeItem.item }}
            </div>
            <div class="dish-edit__recipe-item-actions">
              <v-tooltip
                bottom
                open-delay="300"
              >
                <template #activator="{ on, attrs }">
                  <v-btn
                    icon
                    v-on="on"
                    v-bind="attrs"
                    @click="
                      addRecipeItemId = recipeItem.id;
                      addRecipeItemValue = recipeItem.item;
                      addRecipeItemShow = true;
                      $nextTick(() => $refs.addRecipeItemInput.focus())
                    "
                  >
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                Изменить
              </v-tooltip>
              <v-tooltip
                bottom
                open-delay="300"
              >
                <template #activator="{ on, attrs }">
                  <v-btn
                    icon
                    v-on="on"
                    v-bind="attrs"
                    @click="destroyRecipeItem(recipeItem.id)"
                  >
                    <v-icon>mdi-minus</v-icon>
                  </v-btn>
                </template>
                Удалить
              </v-tooltip>
            </div>
          </div>
          <validation-observer v-if="addRecipeItemShow" slim v-slot="{ invalid }">
            <div class="dish-edit__recipe-item-add">
              <validation-provider rules="required" slim v-slot="{ errors }">
                <v-text-field
                  v-model="addRecipeItemValue"
                  ref="addRecipeItemInput"
                  :prefix="addEditRecipeItemPrefix"
                  :error-messages="errors"
                  @focus="ocRegister('dish-edit__recipe-item-add', () => {
                    closeAddRecipeItem();
                  })"
                />
              </validation-provider>
              <v-btn
                class="ml-4"
                color="primary"
                :outlined="invalid"
                @click="invalid ? closeAddRecipeItem() : addOrUpdateRecipeItem(); ocDrop('dish-edit__recipe-item-add')"
              >
                {{ invalid ? 'Отмена' : addRecipeItemId ? 'Изменить' : 'Добавить' }}
              </v-btn>
            </div>
          </validation-observer>
          <v-btn v-else icon @click="addRecipeItemShow = true; $nextTick(() => $refs.addRecipeItemInput.focus())">
            <v-icon>$add</v-icon>
          </v-btn>
        </div>
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
        <div class="dish-edit__ingredients-container">
          <div class="dish-edit__ingredients-header dish-edit__h2">
            Ингредиенты (на одну порцию):
          </div>
          <div v-for="ingredient of dish.ingredients" :key="ingredient.id" class="dish-edit__ingredient">
            <div class="dish-edit__ingredient-name">
              {{ ingredient.name }}
            </div>
            <div class="dish-edit__ingredient-amount-actions">
              <div class="dish-edit__ingredient-actions">
                <v-tooltip
                  bottom
                  open-delay="300"
                >
                  <template #activator="{ on, attrs }">
                    <v-btn
                      icon
                      v-on="on"
                      v-bind="attrs"
                      @click="
                        addIngredientId = ingredient.id;
                        addIngredientAmount = ingredient.pivot.amount;
                        addIngredientShow = true;
                        $nextTick(() => $refs.addIngredientAmountInput.focus());
                      "
                    >
                      <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                  </template>
                  Изменить количество
                </v-tooltip>
                <v-tooltip
                  bottom
                  open-delay="300"
                >
                  <template #activator="{ on, attrs }">
                    <v-btn
                      icon
                      v-on="on"
                      v-bind="attrs"
                      @click="destroyIngredient(ingredient.id)"
                    >
                      <v-icon>mdi-minus</v-icon>
                    </v-btn>
                  </template>
                  Удалить
                </v-tooltip>
              </div>
              <div class="dish-edit__ingredient-amount">
                {{ ingredient.pivot.amount }} {{ ingredient.unit }}
              </div>
            </div>
          </div>
          <validation-observer v-if="addIngredientShow" slim v-slot="{ invalid }">
            <div class="dish-edit__ingredient-add">
              <div>
                <validation-provider slim rules="required" v-slot="{ errors }">
                  <v-autocomplete
                    v-model="addIngredientId"
                    ref="addIngredientIdInput"
                    :items="availableIngredients"
                    label="Ингредиент"
                    item-text="name"
                    item-value="id"
                    :error-messages="errors"
                    @focus="ocRegister('dish-edit__ingredient-add', () => {
                      closeAddIngredient();
                    }, true)"
                    @input="$refs.addIngredientIdInput.blur(); $refs.addIngredientAmountInput.focus();"
                  />
                </validation-provider>
              </div>
              <validation-provider slim rules="required|min:1" v-slot="{ errors }">
                <div style="width: 20%" class="ml-4">
                  <v-text-field
                    v-model="addIngredientAmount"
                    ref="addIngredientAmountInput"
                    :error="errors.length > 0"
                    :suffix="addIngredientUnit"
                  />
                </div>
              </validation-provider>
              <v-btn
                class="ml-4"
                color="primary"
                :outlined="invalid"
                @click="invalid ? closeAddIngredient() : addIngredient(); ocDrop('dish-edit__ingredient-add')"
              >
                {{ invalid ? 'Отмена' : addIngredientButtonText }}
              </v-btn>
            </div>
          </validation-observer>
          <v-btn v-else icon @click="addIngredientShow = true; $nextTick(() => $refs.addIngredientIdInput.focus())">
            <v-icon>$add</v-icon>
          </v-btn>
        </div>
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
import IngredientRepository from "@/repositories/IngredientRepository";
import {Ingredient, IngredientUnit} from "@/models/Ingredient";
import {nextEnum} from "@/models/common/Enum";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import ImageRepository from "@/repositories/ImageRepository";
import {Image} from "@/models/Image";

@Component({})
export default class DishesEdit extends mixins(OutsideClickMixin) {
  private dish: DishShow | null = null;
  private availableIngredients: Ingredient[] = [];

  private addIngredientShow = false;
  private addIngredientId = 0;
  private addIngredientAmount = 1;
  private addIngredientFakeUnit = IngredientUnit.Grams;

  private addRecipeItemShow = false;
  private addRecipeItemId = 0;
  private addRecipeItemValue = '';

  private selectedImageId = 0;

  private editNameShow = false;

  get selectedImage(): Image | undefined {
    if (!this.dish) {
      return undefined;
    }

    let image: Image | undefined;

    if (this.selectedImageId) {
      image = this.dish.images.find(({id}) => id === this.selectedImageId);
    } else {
      image = this.dish.images[0];
    }

    return image;
  }

  get addIngredientValue(): Ingredient | undefined {
    return this.availableIngredients.find(({id}) => id === this.addIngredientId);
  }

  get addIngredientUnit(): IngredientUnit {
    return this.addIngredientValue ? this.addIngredientValue.unit : this.addIngredientFakeUnit;
  }

  get addIngredientButtonText(): string {
    return this.dish?.ingredients.find(({id}) => id === this.addIngredientId) ? 'Изменить' : 'Добавить'
  }

  get addEditRecipeItemPrefix(): string {
    if (!this.dish) {
      return "1";
    }

    return (this.addRecipeItemId ? this.dish.recipe_items.findIndex(({id}) => id === this.addRecipeItemId) + 1 : this.dish.recipe_items.length + 1).toString();
  }

  mounted(): void {
    this.loadDish();
    this.loadAvailableIngredients();
    setInterval(this.updateFakeUnit, 2000);
  }

  updateFakeUnit(): void {
    this.addIngredientFakeUnit = nextEnum(IngredientUnit, this.addIngredientFakeUnit);
  }

  closeAddIngredient(): void {
    this.addIngredientShow = false;
  }

  closeAddRecipeItem(): void {
    this.addRecipeItemShow = false;
    this.addRecipeItemValue = '';
    this.addRecipeItemId = 0;
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

  async loadAvailableIngredients(): Promise<void> {
    this.availableIngredients = [];
    let page = 1;
    let lastPage = 1;

    while (page <= lastPage) {
      const {data} = await IngredientRepository.paginate(page);

      lastPage = data.last_page;
      page = data.current_page + 1;

      this.availableIngredients.push(...data.data);
    }
  }

  async addOrUpdateRecipeItem(): Promise<void> {
    let response;
    if (this.addRecipeItemId) {
      response = await DishRepository.updateRecipe(this.dishId, this.addRecipeItemId, this.addRecipeItemValue);
    } else {
      response = await DishRepository.storeRecipe(this.dishId, this.addRecipeItemValue);
    }

    this.dish = response.data;

    this.addRecipeItemShow = false;
    this.addRecipeItemId = 0;
    this.addRecipeItemValue = '';
  }

  async addIngredient(): Promise<void> {
    const {data} = await DishRepository.storeIngredient(this.dishId, this.addIngredientId, this.addIngredientAmount);

    this.dish = data;

    this.addIngredientShow = false;
    this.addIngredientId = 0;
    this.addIngredientAmount = 1;
  }

  async destroyIngredient(ingredient_id: number): Promise<void> {
    const {data} = await DishRepository.destroyIngredient(this.dishId, ingredient_id);

    this.dish = data;
  }

  async destroyRecipeItem(recipe_item_id: number): Promise<void> {
    const {data} = await DishRepository.destroyRecipe(this.dishId, recipe_item_id);

    this.dish = data;
  }

  async storeImage(a: InputEvent): Promise<void> {
    const target = a.target as HTMLInputElement;

    if (!target.files || target.files.length < 1) {
      return;
    }

    const file = target.files[0];

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
        display: flex;
        flex-direction: column;

        .dish-edit__image {
          position: relative;
          border-radius: 25px;
          overflow: hidden;
          aspect-ratio: 1.5;

          .dish-edit__image-actions {
            position: absolute;
            top: 10px;
            right: 10px;
          }
        }

        .dish-edit__images {
          margin-top: 12px;
          display: flex;

          .dish-edit__images-item {
            width: 12.5%;
            overflow: hidden;
            border-radius: 5px;
            aspect-ratio: 1;
          }

          .dish-edit__images-item + .dish-edit__images-item {
            margin-left: 2.08%;
          }
        }

        .dish-edit__image-placeholder {
          width: 100%;
          height: 100%;

          display: flex;
          align-items: center;
          justify-content: center;

          background: #FFEDD3;
        }
      }

      .dish-edit__recipe-header {
        margin-bottom: 20px;
      }

      .dish-edit__recipe-item {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 80px;
        padding-left: 40px;
        padding-top: 30px;
        padding-bottom: 10px;

        border-top: 1px solid rgba(0, 0, 0, 0.15);

        &:last-of-type {
          border-bottom: 1px solid rgba(0, 0, 0, 0.15);
        }

        &:hover {
          .dish-edit__recipe-item-actions {
            display: flex;
          }
        }

        .dish-edit__recipe-item-index {
          position: absolute;
          top: 10px;
          left: 10px;

          font-size: 24px;
          font-weight: 600;
          line-height: 29px;
          letter-spacing: 0;
          text-align: left;
        }

        .dish-edit__recipe-item-actions {
          margin-right: 12px;
          display: none;
        }
      }

      .dish-edit__recipe-item-add {
        display: flex;
        align-items: center;
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

        .dish-edit__ingredients-header {
          margin-bottom: 20px;
        }

        .dish-edit__ingredient {
          height: 44px;
          display: flex;
          justify-content: space-between;
          align-items: center;

          border-top: 1px solid rgba(0, 0, 0, 0.15);

          &:last-of-type {
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
          }

          &:hover {
            .dish-edit__ingredient-amount-actions .dish-edit__ingredient-actions {
              display: flex;
            }
          }

          .dish-edit__ingredient-name {

          }

          .dish-edit__ingredient-amount-actions {
            display: flex;
            align-items: center;

            .dish-edit__ingredient-amount {

            }

            .dish-edit__ingredient-actions {
              margin-right: 12px;
              display: none;
            }
          }
        }

        .dish-edit__ingredient-add {
          display: flex;
          align-items: center;
        }
      }
    }

    .dish-edit__h2 {
      font-size: 24px;
      font-weight: 600;
      line-height: 29px;
      letter-spacing: 0;
      text-align: left;
    }
  }
}
</style>
