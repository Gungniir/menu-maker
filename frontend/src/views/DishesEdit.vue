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
            {{ ingredient.name }}
          </div>
          <div v-if="addIngredientShow" class="dish-edit__ingredient-add">
            <v-combobox v-model="addIngredientValue" item-text="name" :items="availableIngredients" />
            <v-btn class="ml-4" color="primary" :outlined="!this.addIngredientValue" @click="addIngredient">
              {{ !this.addIngredientValue ? 'Отмена' : 'Добавить'}}
            </v-btn>
          </div>
          <v-btn icon @click="addIngredientShow = true">
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
import {Ingredient} from "@/models/Ingredient";

@Component({})
export default class DishesEdit extends Vue {
  private dish: DishShow | null = null;
  private availableIngredients: Ingredient[] = [];
  private addIngredientShow = false;
  private addIngredientValue: Ingredient | null = null;

  mounted(): void {
    this.loadDish();
    this.loadAvailableIngredients();
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
    if (!this.addIngredientValue) {
      this.addIngredientShow = false;
      return;
    }

    this.dish?.ingredients.push(this.addIngredientValue)
    this.addIngredientShow = false;
    this.addIngredientValue = null;
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
    }

    .dish-edit__h2 {
      font-size: 24px;
      font-weight: 600;
      line-height: 29px;
      letter-spacing: 0;
      text-align: left;
    }

    .dish-edit__ingredient-add {
      display: flex;
      align-items: center;
    }
  }
}
</style>
