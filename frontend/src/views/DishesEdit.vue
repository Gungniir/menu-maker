<template>
  <div class="dish-edit">
    <div v-if="dish" class="dish-edit__container">
      <div class="dish-edit__left">
        <div class="dish-edit__images-container">
          <div class="dish-edit__image">
            <v-img v-if="dish.images[0]" :src="dish.images[0].filename" />
            <div v-else class="dish-edit__image-placeholder" />
          </div>
          <div class="dish-edit__images">
            <div v-for="image of dish.images" :key="image.id" class="dish-edit__images-item">
              <v-img :src="image.filename" />
            </div>
            <div v-if="dish.images.length < 7" class="dish-edit__images-item">
              <div class="dish-edit__image-placeholder" />
            </div>
          </div>
        </div>
        <div class="dish-edit__recipe">
          <div class="dish-edit__recipe-header dish-edit__h2">
            Способ приготовления:
          </div>
          <div class="dish-edit__recipe-item">

          </div>
          <v-btn icon>
            <v-icon>$add</v-icon>
          </v-btn>
        </div>
      </div>
      <div class="dish-edit__right">
        <div class="dish-edit__name" :title="dish.name">
          {{ dish.name }}
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
                        $refs.addIngredientAmountInput.focus();
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
                <v-autocomplete
                  v-model="addIngredientId"
                  ref="addIngredientIdInput"
                  :items="availableIngredients"
                  label="Ингредиент"
                  item-text="name"
                  item-value="id"
                  autofocus
                  @input="$refs.addIngredientIdInput.blur(); $refs.addIngredientAmountInput.focus();"
                />
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
                :outlined="!addIngredientId || invalid"
                @click="invalid ? closeAddIngredient() : addIngredient()"
              >
                {{ !addIngredientId ? 'Отмена' : addIngredientButtonText }}
              </v-btn>
            </div>
          </validation-observer>
          <v-btn v-else icon @click="addIngredientShow = true">
            <v-icon>$add</v-icon>
          </v-btn>
        </div>
        <!--        <div class="dish-edit__tools-container">-->
        <!--          <div class="dish-edit__tools-header dish-edit__h2">-->
        <!--            Инструменты:-->
        <!--          </div>-->
        <!--          <div class="dish-edit__tool">-->

        <!--          </div>-->
        <!--          <div class="dish-edit__tool-add">-->

        <!--          </div>-->
        <!--          <v-btn icon>-->
        <!--            <v-icon>$add</v-icon>-->
        <!--          </v-btn>-->
        <!--        </div>-->
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
import {Component, Prop, Vue} from 'vue-property-decorator'
import {DishShow} from "@/models/Dish";
import DishRepository from "@/repositories/DishRepository";
import IngredientRepository from "@/repositories/IngredientRepository";
import {Ingredient, IngredientUnit} from "@/models/Ingredient";
import {nextEnum} from "@/models/common/Enum";

@Component({})
export default class DishesEdit extends Vue {
  private dish: DishShow | null = null;
  private availableIngredients: Ingredient[] = [];
  private addIngredientShow = false;
  private addIngredientId = 0;
  private addIngredientAmount = 1;
  private addIngredientFakeUnit = IngredientUnit.Grams;

  get addIngredientValue(): Ingredient | undefined {
    return this.availableIngredients.find(({id}) => id === this.addIngredientId);
  }

  get addIngredientUnit(): IngredientUnit {
    return this.addIngredientValue ? this.addIngredientValue.unit : this.addIngredientFakeUnit;
  }

  get addIngredientButtonText(): string {
    return this.dish?.ingredients.find(({id}) => id === this.addIngredientId) ? 'Изменить' : 'Добавить'
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
    return;
  }

  async loadDish(): Promise<void> {
    const {data} = await DishRepository.show(this.dishId);

    this.dish = data;
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

  async addIngredient(): Promise<void> {
    const {data} = await DishRepository.storeIngredient(this.dishId, this.addIngredientId, this.addIngredientAmount);

    this.dish = data;

    this.addIngredientShow = false;
    this.addIngredientId = 0;
  }

  async destroyIngredient(ingredient_id: number): Promise<void> {
    const {data} = await DishRepository.destroyIngredient(this.dishId, ingredient_id);

    this.dish = data;
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
          border-radius: 25px;
          overflow: hidden;
          aspect-ratio: 1.5;
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

          background: #FFEDD3;
        }
      }
    }

    .dish-edit__right {
      width: 45%;

      .dish-edit__name {
        margin-bottom: 24px;

        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 36px;
        font-weight: 400;
        line-height: 44px;
        letter-spacing: 0;
        text-align: center;
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
