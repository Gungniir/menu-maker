<template>
  <div class="ingredients">
    <div class="ingredients__ingredients-header ingredients__h2 d-flex">
      Ингредиенты (на
      <template v-if="editMode">
        <v-text-field v-model="portions" outlined class="mx-1 flex-grow-0 hide-messages min-height" style="width: 40px; height: 20px" />
      </template>
      <template v-else>
        одну
      </template>
      порцию):
    </div>
    <div v-for="ingredient of sortedIngredients" :key="ingredient.id" class="ingredients__ingredient">
      <div class="ingredients__ingredient-name">
        {{ ingredient.name }}
      </div>
      <div class="ingredients__ingredient-amount-actions">
        <div v-if="editMode" class="ingredients__ingredient-actions">
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
                  addIngredientAmount = Number((Number(ingredient.pivot.amount) * portions).toFixed(3)).toString();
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
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </template>
            Удалить
          </v-tooltip>
        </div>
        <div class="ingredients__ingredient-amount">
          {{ Number(ingredient.pivot.amount) * portions }} {{ ingredient.unit }}
        </div>
      </div>
    </div>
    <template v-if="editMode">
      <validation-observer v-if="addIngredientShow" slim>
        <div class="ingredients__ingredient-add">
          <div>
            <validation-provider slim rules="required" v-slot="{ errors }">
              <v-autocomplete
                v-model="addIngredientId"
                ref="addIngredientIdInput"
                label="Ингредиент"
                item-text="name"
                item-value="id"
                :items="availableIngredients"
                :error-messages="errors"
                :search-input.sync="addIngredientFilter"
                @focus="ocRegister('ingredients__ingredient-add', () => {
                      closeAddIngredient();
                    }, true)"
                @input="onInputIngredientId"
                @keydown.enter="checkNewIngredient"
              />
            </validation-provider>
          </div>
          <validation-provider slim rules="required|decimal:3|min:0" v-slot="{ errors }">
            <div style="width: 20%" class="ml-4">
              <v-text-field
                v-model="addIngredientAmount"
                ref="addIngredientAmountInput"
                :error="errors.length > 0"
                :suffix="addIngredientUnit"
                @keydown.enter="onAddButtonClick();"
              />
            </div>
          </validation-provider>
          <v-btn
            class="ml-4"
            color="primary"
            @click="onAddButtonClick();"
          >
            {{ addIngredientButtonText }}
          </v-btn>
        </div>
      </validation-observer>
      <v-btn v-else icon @click="addIngredientShow = true; $nextTick(() => $refs.addIngredientIdInput.focus())">
        <v-icon>$add</v-icon>
      </v-btn>
    </template>
    <ingredient-add-edit-dialog v-model="showAddDialog" :ingredient-input-name="addIngredientFilter" :used-names="availableIngredients.map(ingredient => ingredient.name)" @created="onCreatedIngredient" />
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop, Watch} from 'vue-property-decorator'
import {Ingredient, IngredientShow, IngredientUnit, IngredientWithDishPivot} from "@/models/Ingredient";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import {mixins} from "vue-class-component";
import {nextEnum} from "@/models/common/Enum";
import IngredientRepository from "@/repositories/IngredientRepository";
import IngredientAddEditDialog from "@/components/IngredientAddEditDialog.vue";
import {ie} from "@/utils";


@Component({
  components: {IngredientAddEditDialog},
})
export default class DishEditIngredients extends mixins(OutsideClickMixin) {
  $refs!: {
    addIngredientIdInput: InstanceType<typeof HTMLInputElement>,
    addIngredientAmountInput: InstanceType<typeof HTMLInputElement>,
  }

  @Prop({required: true}) ingredients!: IngredientWithDishPivot[];
  @Prop({default: false}) editMode!: boolean;

  private availableIngredients: Ingredient[] = [];

  private portions = 1;
  private addIngredientId = 0;
  private addIngredientAmount = '0';
  private addIngredientShow = false;
  private addIngredientFakeUnit = IngredientUnit.Grams;
  private addIngredientFilter = '';
  private showAddDialog = false;

  mounted(): void {
    this.loadAvailableIngredients();
    setInterval(this.updateFakeUnit, 2000);
  }

  get sortedIngredients(): IngredientWithDishPivot[] {
    const i = this.ingredients.slice();
    i.sort((a, b)=> a.name.localeCompare(b.name));
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
    this.addIngredientAmount = '0';
    this.addIngredientShow = false;
  }

  private updateFakeUnit(): void {
    this.addIngredientFakeUnit = nextEnum(IngredientUnit, this.addIngredientFakeUnit);
  }

  private checkNewIngredient(): void {
    const index = this.availableIngredients.findIndex(ing => ie(ing.name, this.addIngredientFilter));
    if (index !== -1) {
      this.addIngredientId = this.availableIngredients[index].id;
    }

    setTimeout(() => {
        if (this.addIngredientValue && this.addIngredientValue.name === this.addIngredientFilter) {
          this.$refs.addIngredientIdInput.blur();
          this.$refs.addIngredientAmountInput.focus();
          return;
        }

        this.ocDrop('ingredients__ingredient-add');
        this.showAddDialog = true;
    }, 100);
  }

  private onInputIngredientId(): void {
    if (this.addIngredientId) {
      this.$refs.addIngredientIdInput.blur();
      this.$refs.addIngredientAmountInput.focus();
    }
  }

  private onCreatedIngredient(ingredient: IngredientShow): void {
    this.availableIngredients.push(ingredient);
    this.addIngredientId = ingredient.id;
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

  private onAddButtonClick(): void {
    this.ocDrop('ingredients__ingredient-add');
    if (!this.addIngredientValue) {
      this.showAddDialog = true;
    } else {
      this.addIngredient();
      this.closeAddIngredient();
    }
  }

  @Emit('add-ingredient')
  addIngredient(): {id: number, amount: string} {
    let amount = Number(this.addIngredientAmount) / this.portions;
    if (amount <= 0) {
      amount = 1;
    }


    return {
      id: this.addIngredientId,
      amount: Number(amount.toFixed(3)).toString(),
    };
  }

  @Emit('destroy-ingredient')
  destroyIngredient(id: number): number {
    return id;
  }

  @Watch('editMode')
  editModeWatcher(): void {
    this.portions = 1;
  }

  @Watch('showAddDialog')
  showAddDialogWatcher(val: boolean): void {
    if (!val) {
      this.ocRegister('ingredients__ingredient-add', () => {
        this.closeAddIngredient();
      }, true);
    }
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
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .ingredients__ingredient-amount-actions {
      flex-shrink: 0;
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
