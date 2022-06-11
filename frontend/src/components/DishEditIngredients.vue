<template>
  <div class="ingredients">
    <div class="ingredients__ingredients-header ingredients__h2">
      Ингредиенты (на одну порцию):
    </div>
    <div v-for="ingredient of sortedIngredients" :key="ingredient.id" class="ingredients__ingredient">
      <div class="ingredients__ingredient-name">
        {{ ingredient.name }}
      </div>
      <div class="ingredients__ingredient-amount-actions">
        <div class="ingredients__ingredient-actions">
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
        <div class="ingredients__ingredient-amount">
          {{ ingredient.pivot.amount }} {{ ingredient.unit }}
        </div>
      </div>
    </div>
    <validation-observer v-if="addIngredientShow" slim v-slot="{ invalid }">
      <div class="ingredients__ingredient-add">
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
              @focus="ocRegister('ingredients__ingredient-add', () => {
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
              @keyup.enter="invalid ? closeAddIngredient() : addIngredient(); closeAddIngredient(); ocDrop('ingredients__ingredient-add')"
            />
          </div>
        </validation-provider>
        <v-btn
          class="ml-4"
          color="primary"
          :outlined="invalid"
          @click="invalid ? closeAddIngredient() : addIngredient(); closeAddIngredient(); ocDrop('ingredients__ingredient-add')"
        >
          {{ invalid ? 'Отмена' : addIngredientButtonText }}
        </v-btn>
      </div>
    </validation-observer>
    <v-btn v-else icon @click="addIngredientShow = true; $nextTick(() => $refs.addIngredientIdInput.focus())">
      <v-icon>$add</v-icon>
    </v-btn>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop} from 'vue-property-decorator'
import {Ingredient, IngredientUnit, IngredientWithDishPivot} from "@/models/Ingredient";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import {mixins} from "vue-class-component";
import {nextEnum} from "@/models/common/Enum";
import IngredientRepository from "@/repositories/IngredientRepository";

@Component({})
export default class DishEditIngredients extends mixins(OutsideClickMixin) {
  @Prop({required: true}) ingredients!: IngredientWithDishPivot[];

  private availableIngredients: Ingredient[] = [];

  private addIngredientId = 0;
  private addIngredientAmount = 1;
  private addIngredientShow = false;
  private addIngredientFakeUnit = IngredientUnit.Grams;

  mounted(): void {
    this.loadAvailableIngredients();
    setInterval(this.updateFakeUnit, 2000);
  }

  get sortedIngredients(): IngredientWithDishPivot[] {
    const i = this.ingredients.slice();
    i.sort(({id: id_a}, {id: id_b}) => id_a - id_b);
    return i;
  }

  get addIngredientValue(): Ingredient | undefined {
    return this.availableIngredients.find(({id}) => id === this.addIngredientId);
  }

  get addIngredientUnit(): IngredientUnit {
    return this.addIngredientValue ? this.addIngredientValue.unit : this.addIngredientFakeUnit;
  }

  get addIngredientButtonText(): string {
    return this.ingredients.find(({id}) => id === this.addIngredientId) ? 'Изменить' : 'Добавить'
  }

  private closeAddIngredient(): void {
    this.addIngredientId = 0;
    this.addIngredientAmount = 1;
    this.addIngredientShow = false;
  }

  private updateFakeUnit(): void {
    this.addIngredientFakeUnit = nextEnum(IngredientUnit, this.addIngredientFakeUnit);
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

  @Emit('add-ingredient')
  addIngredient(): {id: number, amount: number} {
    return {
      id: this.addIngredientId,
      amount: this.addIngredientAmount,
    };
  }

  @Emit('destroy-ingredient')
  destroyIngredient(id: number): number {
    return id;
  }
}
</script>

<style scoped lang="scss">
.ingredients {
  .ingredients__ingredients-header {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: 600;
    line-height: 29px;
    letter-spacing: 0;
    text-align: left;
  }

  .ingredients__ingredient {
    height: 44px;
    display: flex;
    justify-content: space-between;
    align-items: center;

    border-top: 1px solid rgba(0, 0, 0, 0.15);

    &:last-of-type {
      border-bottom: 1px solid rgba(0, 0, 0, 0.15);
    }

    &:hover {
      .ingredients__ingredient-amount-actions .ingredients__ingredient-actions {
        display: flex;
      }
    }

    .ingredients__ingredient-name {

    }

    .ingredients__ingredient-amount-actions {
      display: flex;
      align-items: center;

      .ingredients__ingredient-amount {

      }

      .ingredients__ingredient-actions {
        margin-right: 12px;
        display: none;
      }
    }
  }

  .ingredients__ingredient-add {
    display: flex;
    align-items: center;
  }
}
</style>
