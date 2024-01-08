<template>
  <div class="dishes">
    <div class="dishes__wrapper">
      <div class="dishes__header d-flex justify-center">
        Категории
      </div>
      <div ref="intersectionObserverRoot" class="dishes__container">
        <template v-if="loading && categories.length === 0">
          <v-skeleton-loader class="fluid" type="image" style="aspect-ratio: 1.5" />
          <div />
          <div />
          <v-skeleton-loader v-for="index in 5" :key="index" class="fluid" type="image" style="aspect-ratio: 1.5" />
        </template>
        <template v-else>
          <category-card hide-actions :category="fakeCategory" @click="$router.push('/dishes')" />
          <div />
          <div />
          <category-card
            v-for="(category, index) of categories"
            :key="category.id"
            :category="category"
            :color="colors[index + 2 % colors.length]"
            @click="$router.push(`/categories/${category.id}`)"
            @deleted="categories.splice(index, 1)"
            @edit="selectedCategoryId = category.id; showAddEditDialog = true"
          />
        </template>
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
          @click="selectedCategoryId = 0; showAddEditDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить категорию
    </v-tooltip>
    <category-add-edit-dialog
      v-model="showAddEditDialog"
      :category-id="selectedCategoryId"
      @created="onCreatedEvent"
      @updated="onUpdatedEvent"
    />
  </div>
</template>

<script setup lang="ts">
import { CategoryIndex, CategoryShow } from '@/models/Category'
import CategoryCard from '@/components/CategoryCard.vue'
import CategoryRepository from '@/repositories/CategoryRepository'
import CategoryAddEditDialog from '@/components/CategoryAddEditDialog.vue'
import { onBeforeUnmount, onMounted, ref } from 'vue'

const lastPage = ref(1)
const page = ref(1)
const loading = ref(true)
const colors = ref(['#CAFCDE', '#FCE8CA', '#E5E5E5', '#FCCACA', '#D8EFFF', '#FBFCCA', '#F9F9F9', '#ECCAFC', '#CBFCCA'])
const showAddEditDialog = ref(false)
const selectedCategoryId = ref(0)

const fakeCategory = {
  name: 'Все блюда',
} as CategoryIndex
const categories = ref<CategoryIndex[]>([])

onMounted(() => {
  fetchMoreCategories()
})

const fetchMoreCategories = async () => {
  if (lastPage.value < page.value) {
    return
  }
  loading.value = true

  const { data } = await CategoryRepository.paginate(page.value++)

  categories.value.push(...data.data)
  lastPage.value = data.last_page
  loading.value = false
}

const onCreatedEvent = (category: CategoryShow) => {
  if (page.value > lastPage.value) {
    categories.value.push(category)
  }
}

const onUpdatedEvent = (category: CategoryShow) => {
  const index = categories.value.findIndex((c) => c.id === category.id)

  if (index === -1) return

  categories.value.splice(index, 1, category)
}

const intersectionObserverRoot = ref(null)
const intersectionObserverElement = ref(null)
let intersectionObserver = null as IntersectionObserver
onMounted(() => {
  intersectionObserver = new IntersectionObserver((entries) => {
    onIntersectionCallback(entries[0].isIntersecting)
  }, {
    root: intersectionObserverRoot.value,
  })

  intersectionObserver.observe(intersectionObserverElement.value)
})
onBeforeUnmount(() => {
  intersectionObserver.disconnect()
})

const onIntersectionCallback = (visible: boolean) => {
  if (loading.value || !visible) {
    return
  }
  fetchMoreCategories()
}
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
