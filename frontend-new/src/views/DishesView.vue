<template>
  <div class="dishes">
    <div class="dishes__wrapper">
      <div class="dishes__header d-flex justify-center">
        <v-skeleton-loader v-if="categoryId && !category" type="image" height="50" width="250" />
        <template v-else-if="category">{{ category.name }}</template>
        <template v-else>Все блюда</template>
      </div>
      <div ref="intersectionObserverRoot" class="dishes__container">
        <template v-if="loading && dishes.length === 0">
          <v-skeleton-loader v-for="index in 5" :key="index" class="fluid" type="image" style="aspect-ratio: 1.5" />
        </template>
        <DishCard
          v-for="(dish, index) of dishes"
          :key="dish.id"
          :dish="dish"
          @click="router.push(`/dishes/${dish.id}`)"
          @deleted="dishes.splice(index, 1)"
        />
        <div ref="intersectionObserverElement" class="dishes__intersection-observer" />
      </div>
    </div>
    <v-tooltip
      open-delay="300"
      bottom
    >
      <template #activator="{ attrs }">
        <v-btn
          color="primary"
          fab
          fixed
          bottom
          right
          style="bottom: 50px; right: calc(13vw + 25px)"
          v-bind="attrs"
          @click="showAddDialog = true; newDishName = ''; addDialog.reset()"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить блюдо
    </v-tooltip>
    <dialog-card ref="addDialog" v-model="showAddDialog" with-observer title="Добавить блюдо" v-slot="{ invalid }">
      <v-row no-gutters>
        <v-col>
          <v-text-field
            -model="newDishName"
            v-bind="newDishNameProps"
            outlined
            color="primary"
            label="Название"
            persistent-placeholder
            :error-messages="errors.name"
          />
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col class="d-flex justify-end">
          <v-btn color="primary" outlined large @click="showAddDialog = false">Отмена</v-btn>
          <v-btn class="ml-4" color="primary" large :disabled="invalid" @click="storeDish">Создать</v-btn>
        </v-col>
      </v-row>
    </dialog-card>
  </div>
</template>

<script lang="ts" setup>
import DishRepository from '@/repositories/DishRepository'
import { DishEntity, DishIndex } from '@/models/Dish'
import DishCard from '@/components/DishCard.vue'
import DialogCard from '@/components/DialogCard.vue'
import { Category } from '@/models/Category'
import CategoryRepository from '@/repositories/CategoryRepository'
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/valibot'
import { minLength, object, string } from 'valibot'

const props = withDefaults(defineProps<{
  categoryId?: number
}>(), {
  categoryId: 0,
})

const router = useRouter()

const dishes = ref<DishIndex[]>([])
const category = ref<Category|null>(null)
const lastPage = ref(1)
const page = ref(1)
const loading = ref(false)
const showAddDialog = ref(false)

const addDialog = ref(null)

const { errors, defineField } = useForm({
  validationSchema: toTypedSchema(object({
    name: string('Обязательное поле', [minLength(1, 'Обязательное поле')]),
  })),
})

const [newDishName, newDishNameProps] = defineField('name', state => ({
  validateOnModelUpdate: state.errors.length > 0,
}))

const intersectionObserverRoot = ref(null)
const intersectionObserverElement = ref(null)

const intersectionObserver = new IntersectionObserver((entries) => {
  const visible = entries[0].isIntersecting

  if (loading.value || !visible) {
    return
  }

  fetchMoreDishes()
}, {
  root: intersectionObserverRoot.value,
})

onMounted(() => {
  intersectionObserver.observe(intersectionObserverElement.value)

  fetchMoreDishes()

  if (props.categoryId) {
    loadCategory()
  }
})

onBeforeUnmount(() => {
  intersectionObserver.disconnect()
})

const storeDish = async () => {
  // todo: Нужно будет переделать типы для сохранения
  const { data } = await DishRepository.store({ name: newDishName.value } as DishEntity)

  await router.push(`/dishes/${data.id}/edit`)
}

const fetchMoreDishes = async () => {
  if (lastPage.value < page.value) {
    return
  }
  loading.value = true

  const { data } = await DishRepository.paginate(page.value++, props.categoryId ?? undefined)

  dishes.value.push(...data.data)
  lastPage.value = data.last_page
  loading.value = false
}

watch(() => props.categoryId, async (val) => {
  const { data } = await CategoryRepository.show(val)

  category.value = data
})
</script>

<style scoped lang="scss">
.dishes {
  position: relative;
  width: 100%;
  height: 100%;

  .dishes__wrapper {
    .dishes__header {
      font-size: 40px;
      font-weight: 400;
      line-height: 49px;
      letter-spacing: 0;
      text-align: center;

      margin-bottom: 40px;
    }

    .dishes__container {
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      grid-gap: 30px;
    }
  }
}
</style>
