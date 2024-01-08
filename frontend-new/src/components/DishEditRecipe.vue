<template>
  <div class="recipe">
    <div class="recipe__header">
      Способ приготовления:
    </div>

    <div v-for="(recipeItem, index) of sortedRecipeItems" :key="recipeItem.id" class="recipe__item">
      <div class="recipe__item-index">
        {{ index + 1 }}
      </div>
      <div class="recipe__item-name">
        {{ recipeItem.item }}
      </div>
      <div v-if="editMode" class="recipe__item-actions">
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
              @click="emits('destroy-recipe-item', recipeItem.id)"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          Удалить
        </v-tooltip>
      </div>
    </div>
    <template v-if="editMode">
      <div v-if="addRecipeItemShow" class="recipe__item-add">
        <v-textarea
          v-model="addRecipeItemValue"
          v-bind="addRecipeItemValueProps"
          ref="addRecipeItemInput"
          rows="3"
          auto-grow
          :prefix="addEditRecipeItemPrefix"
          :error-messages="errors.addRecipeItemValue"
          @focus="
            registerOC('recipe__item-add', () => {
              closeAddRecipeItem();
            })
          "
          @keyup.enter="invalid ? closeAddRecipeItem() : addOrUpdateRecipeItem(); dropOC('recipe__item-add')"
        />
        <v-btn
          class="ml-4"
          color="primary"
          :outlined="invalid"
          @click="invalid ? closeAddRecipeItem() : addOrUpdateRecipeItem(); dropOC('recipe__item-add')"
        >
          {{ invalid ? 'Отмена' : addRecipeItemId ? 'Изменить' : 'Добавить' }}
        </v-btn>
      </div>
      <v-btn v-else icon @click="addRecipeItemShow = true; $nextTick(() => $refs.addRecipeItemInput.focus())">
        <v-icon>$add</v-icon>
      </v-btn>
    </template>
  </div>
</template>

<script lang="ts" setup>
import type { RecipeItem } from '@/models/RecipeItem'
import { useForm, useIsFormDirty, useIsFormValid } from 'vee-validate'
import { computed, ref } from 'vue'
import { toTypedSchema } from '@vee-validate/valibot'
import { minLength, object, string } from 'valibot'
import useOutsideClickHandler from '@/hooks/OutsideClickHandler'

const { registerOC, dropOC } = useOutsideClickHandler()

const props = withDefaults(defineProps<{
  recipeItems: RecipeItem[],
  editMode?: boolean
}>(), {
  editMode: false,
})

const emits = defineEmits<{
  (e: 'update-recipe-item', data: { id: number, value: string }),
  (e: 'store-recipe-item', name: string),
  (e: 'destroy-recipe-item', id: number)
}>()

const { errors, defineField, resetForm } = useForm({
  validationSchema: toTypedSchema(object({
    addRecipeItemValue: string('Обязательное поле', [minLength(1, 'Обязательное поле')]),
  })),
})

const isFormValid = useIsFormValid()
const isFormDirty = useIsFormDirty()

const invalid = computed(() => !isFormValid || isFormDirty)

const [addRecipeItemValue, addRecipeItemValueProps] = defineField('addRecipeItemValue', state => ({
  validateOnModelUpdate: state.errors.length > 0
}))

const addRecipeItemId = ref(0)
const addRecipeItemShow = ref(false)

const closeAddRecipeItem = () => {
  addRecipeItemShow.value = false
  addRecipeItemValue.value = ''
  addRecipeItemId.value = 0
}

const addOrUpdateRecipeItem = () => {
  if (addRecipeItemId.value) {
    emits('update-recipe-item', {
      id: addRecipeItemId.value,
      value: addRecipeItemValue.value,
    })
    closeAddRecipeItem()
  } else {
    emits('store-recipe-item', addRecipeItemValue.value)
    addRecipeItemValue.value = ''
    addRecipeItemId.value = 0
    resetForm()
  }
}

const addEditRecipeItemPrefix = computed(() => {
  return (addRecipeItemId.value ? sortedRecipeItems.value.findIndex(({ id }) => id === addRecipeItemId.value) + 1 : props.recipeItems.length + 1).toString()
})

const sortedRecipeItems = computed(() => {
  const ri = props.recipeItems.slice()
  ri.sort(({ id: id_a }, { id: id_b }) => id_a - id_b)

  return ri
})
</script>

<style scoped lang="scss">


.recipe__header {
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: 600;
  line-height: 29px;
  letter-spacing: 0;
  text-align: left;
}

.recipe__item {
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
    .recipe__item-actions {
      opacity: 1;
    }
  }

  .recipe__item-index {
    position: absolute;
    top: 10px;
    left: 10px;

    font-size: 24px;
    font-weight: 600;
    line-height: 29px;
    letter-spacing: 0;
    text-align: left;
  }

  .recipe__item-actions {
    display: flex;
    margin-right: 12px;
    opacity: 0;
  }
}

.recipe__item-add {
  display: flex;
  align-items: center;
}
</style>
