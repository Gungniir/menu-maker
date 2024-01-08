<template>
  <div class="ingredients">
    <div class="ingredients__ingredients-header ingredients__h2 d-flex">
      Ингредиенты (на
      <template v-if="this.editMode">
        <v-text-field v-model="portions" outlined class="mx-1 flex-grow-0 hide-messages min-height"
                      style="width: 40px; height: 20px" />
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
                  addIngredientAmount = ingredient.pivot.amount * portions;
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
          {{ ingredient.pivot.amount * portions }} {{ ingredient.unit }}
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
                @focus="registerOC('ingredients__ingredient-add', () => {
                      closeAddIngredient();
                    }, true)"
                @input="onInputIngredientId"
                @keydown.enter="checkNewIngredient"
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
    <ingredient-add-edit-dialog v-model="showAddDialog" :ingredient-input-name="addIngredientFilter"
                                :used-names="availableIngredients.map(ingredient => ingredient.name)"
                                @created="onCreatedIngredient" />
  </div>
</template>

<script lang="ts" setup>
import { Component, Emit, Prop, Watch } from 'vue-property-decorator'
import type { Ingredient, IngredientShow, IngredientUnit, IngredientWithDishPivot } from '@/models/Ingredient'
import OutsideClickMixin from '@/mixins/OutsideClickMixin'
import { mixins } from 'vue-class-component'
import { nextEnum } from '@/models/common/Enum'
import IngredientRepository from '@/repositories/IngredientRepository'
import IngredientAddEditDialog from '@/components/IngredientAddEditDialog.vue'
import { ie } from '@/utils'
import { computed, onMounted, ref } from 'vue'
import useOutsideClickHandler from '@/hooks/OutsideClickHandler'

const { registerOC, dropOC } = useOutsideClickHandler()

const props = withDefaults(defineProps<{
  ingredients: IngredientWithDishPivot[],
  editMode?: boolean
}>(), {
  editMode: false,
})

const addIngredientIdInput = <HTMLInputElement>ref(null)
const addIngredientAmountInput = <HTMLInputElement>ref(null)

const availableIngredients = ref<Ingredient[]>([])

const portions = ref(1)
const addIngredientId = ref(0)
const addIngredientAmount = ref(1)
const addIngredientShow = ref(false)
const addIngredientFakeUnit = ref(IngredientUnit.Grams)
const addIngredientFilter = ref('')
const showAddDialog = ref(false)

onMounted(() => {
  loadAvailableIngredients()
  setInterval(updateFakeUnit, 2000)
})

const sortedIngredients = computed(() => {
  const i = props.ingredients.slice()
  i.sort((a, b) => a.name.localeCompare(b.name))
  return i
})

const addIngredientValue = computed(() => {
  return availableIngredients.value.find(({ id }) => id === addIngredientId.value)
})

const addIngredientUnit = computed(() => {
  return addIngredientValue.value ? addIngredientValue.value.unit : addIngredientFakeUnit.value
})

const addIngredientButtonText = computed(() => {
  return props.ingredients.find(({ id }) => id === addIngredientId.value) ? 'Изменить' : 'Добавить'
})

const closeAddIngredient = () => {
  addIngredientId.value = 0
  addIngredientAmount.value = 1
  addIngredientShow.value = false
}

@Component({
  components: { IngredientAddEditDialog },
})
export default class DishEditIngredients extends mixins(OutsideClickMixin) {

  get 

  get 

  get 

  private 

  private updateFakeUnit (): void {
    this.addIngredientFakeUnit = nextEnum(IngredientUnit, this.addIngredientFakeUnit)
  }

  private checkNewIngredient (): void {
    const index = this.availableIngredients.findIndex(ing => ie(ing.name, this.addIngredientFilter))
    if (index !== -1) {
      this.addIngredientId = this.availableIngredients[index].id
    }

    setTimeout(() => {
      if (this.addIngredientValue && this.addIngredientValue.name === this.addIngredientFilter) {
        this.$refs.addIngredientIdInput.blur()
        this.$refs.addIngredientAmountInput.focus()
        return
      }

      this.ocDrop('ingredients__ingredient-add')
      this.showAddDialog = true
    }, 100)
  }

  private onInputIngredientId (): void {
    if (this.addIngredientId) {
      this.$refs.addIngredientIdInput.blur()
      this.$refs.addIngredientAmountInput.focus()
    }
  }

  private onCreatedIngredient (ingredient: IngredientShow): void {
    this.availableIngredients.push(ingredient)
    this.addIngredientId = ingredient.id
  }

  async loadAvailableIngredients (): Promise<void> {
    this.availableIngredients = []
    let page = 1
    let lastPage = 1

    while (page <= lastPage) {
      const { data } = await IngredientRepository.paginate(page)

      lastPage = data.last_page
      page = data.current_page + 1

      this.availableIngredients.push(...data.data)
    }
  }

  private onAddButtonClick (): void {
    this.ocDrop('ingredients__ingredient-add')
    if (!this.addIngredientValue) {
      this.showAddDialog = true
    } else {
      this.addIngredient()
      this.closeAddIngredient()
    }
  }

  @Emit('add-ingredient')
  addIngredient (): { id: number, amount: number } {
    let amount = Math.round(this.addIngredientAmount / this.portions)
    if (amount === 0) {
      amount = 1
    }

    return {
      id: this.addIngredientId,
      amount: amount,
    }
  }

  @Emit('destroy-ingredient')
  destroyIngredient (id: number): number {
    return id
  }

  @Watch('editMode')
  editModeWatcher (): void {
    this.portions = 1
  }

  @Watch('showAddDialog')
  showAddDialogWatcher (val: boolean): void {
    if (!val) {
      this.ocRegister('ingredients__ingredient-add', () => {
        this.closeAddIngredient()
      }, true)
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
