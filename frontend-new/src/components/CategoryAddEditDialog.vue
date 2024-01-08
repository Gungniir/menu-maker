<template>
  <dialog-card
    v-model="opened"
    v-slot="{ invalid }"
    :title="(categoryId ? 'Изменить' : 'Добавить') + ' категорию'"
  >
    <v-row no-gutters="no-gutters">
      <v-col>
        <v-text-field
          v-model="categoryName"
          v-bind="categoryNameProps"
          outlined
          color="primary"
          label="Название"
          persistent-placeholder="persistent-placeholder"
          :error-messages="errors.name"
          :loading="categoryLoading"
        />
      </v-col>
    </v-row>
    <v-row no-gutters="no-gutters">
      <v-col>
        <v-autocomplete
          v-model="dishSelectedId"
          v-model:search="dishSearch"
          outlined
          item-text="name"
          item-value="id"
          item-disabled="disabled"
          label="Добавить блюдо в категорию"
          persistent-placeholder="persistent-placeholder"
          :loading="dishLoading"
          :items="dishes"
        />
      </v-col>
    </v-row>
    <v-row v-if="dishesSelected.length > 0" no-gutters="no-gutters" class="mb-6">
      <v-col>
        Блюда в категории:
        <ul>
          <li v-for="(dish, index) of dishesSelected" :key="dish.id">
            <div class="d-flex justify-space-between align-center">
              {{ dish.name }}
              <v-btn icon @click="dishesSelected.splice(index, 1)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </div>
          </li>
        </ul>
      </v-col>
    </v-row>
    <v-row no-gutters="no-gutters">
      <v-col class="d-flex justify-end">
        <v-btn color="primary" outlined large @click="opened = false">Отмена</v-btn>
        <v-btn class="ml-4" color="primary" large :disabled="invalid" @click="onSubmit">
          {{ categoryId ? 'Изменить' : 'Создать' }}
        </v-btn>
      </v-col>
    </v-row>
  </dialog-card>
</template>

<script lang="ts" setup>
import DialogCard from '@/components/DialogCard.vue'
import type { Dish, DishIndex } from '@/models/Dish'
import CategoryRepository from '@/repositories/CategoryRepository'
import { CategoryShow } from '@/models/Category'
import DishRepository from '@/repositories/DishRepository'
import { computed, nextTick, ref, watch } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/valibot'
import { minLength, object, string } from 'valibot'
import { useNotifierStore } from '@/stores/notifier'

type DishWithDisabled = DishIndex & {
  disabled: boolean
}

const { notify } = useNotifierStore()

const props = withDefaults(defineProps<{
  modelValue?: boolean,
  categoryId?: number
}>(), {
  modelValue: false,
  categoryId: 0,
})

const emits = defineEmits<{
  (e: 'created', category: CategoryShow): void,
  (e: 'updated', category: CategoryShow): void,
  (e: 'input', value: boolean): void,
}>()

const opened = ref(false)
const categoryLoading = ref(false)

const dishes = ref<DishWithDisabled[]>([])
const dishLoading = ref(false)
const dishSearch = ref('')
const dishSelectedId = ref(0)
const dishesSelected = ref<Dish[]>([])

const dishSelected = computed(() => {
  return dishes.value.find(dish => dish.id === dishSelectedId.value)
})

const { errors, defineField, validate } = useForm({
  validationSchema: toTypedSchema(object({
    name: string('Обязательное поле', [minLength(1, 'Обязательное поле')])
  }))
})

const [categoryName, categoryNameProps] = defineField('name', state => ({
  validateOnModelUpdate: state.errors.length > 0,
}))


const reset = () => {
  categoryName.value = ''
  dishLoading.value = false
  dishSearch.value = ''
  dishSelectedId.value = 0
  dishesSelected.value = []
}

const onSubmit = () => {
  if (props.categoryId) {
    updateCategory()
  } else {
    storeCategory()
  }
}

const storeCategory = async () => {
  if (!(await validate()).valid) {
    notify('Проверьте красные поля', 'error')
    return
  }

  const { data } = await CategoryRepository.store({
    name: categoryName.value,
    dishes: dishesSelected.value.map(dish => dish.id),
  })

  opened.value = false

  emits('created', data)
}

const updateCategory = async () => {
  if (!(await validate()).valid) {
    notify('Проверьте красные поля', 'error')
    return
  }

  const { data } = await CategoryRepository.update(props.categoryId, {
    name: categoryName.value,
    dishes: dishesSelected.value.map(dish => dish.id),
  })

  opened.value = false

  emits('updated', data)
}

const loadDishes = async () => {
  if (dishSelected.value?.name === dishSearch.value) return

  dishLoading.value = true
  const search = dishSearch.value

  await delay(200)
  if (search !== dishSearch.value) return

  const { data } = await DishRepository.paginate(1, undefined, search)

  if (search !== dishSearch.value) return

  dishes.value = data.data.map(dish => ({
    ...dish,
    disabled: dishesSelected.value.findIndex(d => d.id === dish.id) !== -1,
  }))
  dishLoading.value = false
}

watch(dishSearch, loadDishes)

const addDish = () => {
  if (!dishSelected.value) return

  dishesSelected.value.push(dishSelected.value)
  dishesSelected.value.sort((a, b) => a.name.localeCompare(b.name))
  dishSelected.value.disabled = true
  
  nextTick(() => {
    dishSelectedId.value = 0
    dishSearch.value = ''
  })
}

watch(dishSelected, addDish)

const loadCategory = async () => {
  categoryLoading.value = true

  const { data } = await CategoryRepository.show(props.categoryId)

  categoryName.value = data.name
  dishesSelected.value = data.dishes
  dishesSelected.value.sort((a, b) => a.name.localeCompare(b.name))

  categoryLoading.value = false
}

const delay = (n: number) => {
  return new Promise(function (resolve) {
    setTimeout(resolve, n)
  })
}

watch(opened, (val) => emits('input', val))
watch(() => props.modelValue, (val) => {
  opened.value = val

  if (val) {
    reset()

    if (props.categoryId) {
      loadCategory()
    }
  }
})
</script>

<style scoped lang="scss">

</style>
