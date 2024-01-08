<template>
  <div class="dish-edit">
    <div class="dish-edit__actions-left">
      <v-tooltip
        v-if="previousPath"
        bottom
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn icon v-on="on" v-bind="attrs" @click="$router.push(previousPath)">
            <v-icon>mdi-arrow-left</v-icon>
          </v-btn>
        </template>
        Вернуться назад
      </v-tooltip>
    </div>
    <div class="dish-edit__actions">
      <v-tooltip
        v-if="!editMode"
        bottom
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn icon v-on="on" v-bind="attrs" @click="$router.push(`/dishes/${dishId}/edit`)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>
        Редактировать
      </v-tooltip>
      <v-tooltip
        v-if="editMode"
        bottom
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn icon v-on="on" v-bind="attrs" @click="$router.push(`/dishes/${dishId}`)">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
        Закончить редактирование
      </v-tooltip>
      <v-tooltip
        bottom
        open-delay="300"
      >
        <template #activator="{ on, attrs }">
          <v-btn icon v-on="on" v-bind="attrs" @click="showDeletePrompt = true">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
        Удалить блюдо
      </v-tooltip>
    </div>
    <div v-if="dish" class="dish-edit__container">
      <div class="dish-edit__left">
        <DishEditImages
          class="dish-edit__images-container"
          :images="dish.images"
          :edit-mode="editMode"
          :loading="imageLoading"
          @store-image="storeImage"
          @detach-image="detachImage"
        />
        <DishEditRecipe
          class="dish-edit__recipe"
          :recipe-items="dish.recipe_items"
          :edit-mode="editMode"
          @destroy-recipe-item="destroyRecipeItem"
          @store-recipe-item="storeRecipeItem"
          @update-recipe-item="updateRecipeItem"
        />
      </div>
      <div class="dish-edit__right">
        <div class="dish-edit__name-container">
          <template v-if="editMode">
            <template v-if="!editNameShow">
              <div
                class="dish-edit__name edit"
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
                    <v-btn
                      icon
                      v-on="on"
                      v-bind="attrs"
                      @click="editNameShow = true; $nextTick(() => $refs.editNameInput.focus())"
                    >
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
                @focus="registerOC('dish-edit__name-input', () => {
                  editNameShow = false;
                  updateDish();
                })"
                @keyup.enter.esc="
                  dropOC('dish-edit__name-input');
                  editNameShow = false;
                  updateDish();
                "
              />
            </div>
          </template>
          <div
            v-else
            class="dish-edit__name"
            :title="dish.name"
          >
            {{ dish.name }}
          </div>
        </div>
        <DishEditIngredients
          class="dish-edit__ingredients-container"
          :ingredients="dish.ingredients"
          :edit-mode="editMode"
          @add-ingredient="addIngredient"
          @destroy-ingredient="destroyIngredient"
        />
        <div v-if="false" class="dish-edit__cooking-time-container">
          <div class="dish-edit__cooking-time-header dish-edit__h2">
            Время приготовления:
          </div>
          <div class="dish-edit__cooking-time">

          </div>
        </div>
      </div>
    </div>
    <dialog-confirm
      v-model="showDeletePrompt"
      title="Удаление блюда"
      text="Вы действительно хотите удалить это блюдо?"
      @confirm="destroyDish"
    />
  </div>
</template>

<script lang="ts" setup>
import { DishShow } from '@/models/Dish'
import DishRepository from '@/repositories/DishRepository'
import ImageRepository from '@/repositories/ImageRepository'
import DishEditImages from '@/components/DishEditImages.vue'
import DishEditRecipe from '@/components/DishEditRecipe.vue'
import DishEditIngredients from '@/components/DishEditIngredients.vue'
import DialogConfirm from '@/components/DialogConfirm.vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHistoryStore } from '@/stores/history'
import useOutsideClickHandler from '@/hooks/OutsideClickHandler'

const route = useRoute()
const router = useRouter()
const { history } = useHistoryStore()
const { registerOC, dropOC } = useOutsideClickHandler()

const props = defineProps<{
  dishId: number
}>()

const dish = ref<DishShow | null>(null)
const editNameShow = ref(false)
const showDeletePrompt = ref(false)
const imageLoading = ref(false)
const previousPath = ref('')

const editMode = computed(() => route.name === 'DishEdit')

onMounted(() => {
  loadDish()

  const route = history[history.length - 1]
  if (['Category', 'Dishes'].includes(route.name)) {
    previousPath.value = route.path
  }
})

const loadDish = async () => {
  const { data } = await DishRepository.show(props.dishId)

  dish.value = data
}

const updateDish = async () => {
  if (dish.value === null) {
    return
  }

  await DishRepository.update(props.dishId, dish.value)
}

const updateRecipeItem = async ({ id, value }: { id: number, value: string }) => {
  const { data } = await DishRepository.updateRecipe(props.dishId, id, value)

  dish.value = data
}

const storeRecipeItem = async (value: string) => {
  const { data } = await DishRepository.storeRecipe(props.dishId, value)

  dish.value = data
}

const addIngredient = async ({ id, amount }: { id: number, amount: number }) => {
  const { data } = await DishRepository.storeIngredient(props.dishId, id, amount)

  dish.value = data
}

const destroyIngredient = async (ingredientId: number) => {
  const { data } = await DishRepository.destroyIngredient(props.dishId, ingredientId)

  dish.value = data
}

const destroyRecipeItem = async (recipeItemId: number) => {
  const { data } = await DishRepository.destroyRecipe(props.dishId, recipeItemId)

  dish.value = data
}

const storeImage = async (file: File) => {
  imageLoading.value = true

  try {
    const { data: imageData } = await ImageRepository.store('Image for dish ' + dishId.value, file)
    const { data: dish } = await DishRepository.attachImage(dishId.value, imageData.id)
    dish.value = dish
  } finally {
    imageLoading.value = false
  }
}

const detachImage = async (id: number) => {
  const { data: dish } = await DishRepository.detachImage(dishId.value, id)

  dish.value = dish
}

const destroyDish = async () => {
  await DishRepository.destroy(props.dishId)

  await router.push('/dishes')
}
</script>

<style scoped lang="scss">
.dish-edit {
  position: relative;
  width: 100%;

  .dish-edit__actions {
    position: absolute;
    top: 10px;
    right: 10px;
  }

  .dish-edit__actions-left {
    position: absolute;
    top: 10px;
    left: 10px;
  }

  .dish-edit__container {
    width: 100%;
    padding: 70px;
    display: flex;

    .dish-edit__left {
      width: 55%;
      padding-right: 30px;

      .dish-edit__images-container {
        padding-bottom: 40px;
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
          border-radius: 10px;
          transition: background-color cubic-bezier(0.25, 0.1, 0.25, 1) 300ms;

          &.edit {
            cursor: pointer;
          }
        }

        &:hover {
          .dish-edit__name-actions {
            opacity: 1;
          }

          .dish-edit__name.edit {
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
      }
    }
  }
}
</style>
