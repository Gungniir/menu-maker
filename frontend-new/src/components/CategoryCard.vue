<template>
  <div class="dc" @click="clickEvent">
    <div class="dc__background" :style="{background: color}">
      {{ category.name }}
    </div>
    <div v-if="!hideActions" class="dc__actions">
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on" @click.stop="editEvent">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>
        Редактировать
      </v-tooltip>
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on" @click.stop="destroyCategoryPrompt">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
        Удалить
      </v-tooltip>
    </div>
    <dialog-confirm v-model="showDeletePrompt" title="Удаление категории"
                    text="Вы действительно хотите удалить эту категорию?" @confirm="destroyCategory" />
  </div>
</template>

<script lang="ts" setup>
import DialogConfirm from '@/components/DialogConfirm.vue'
import { CategoryIndex } from '@/models/Category'
import CategoryRepository from '@/repositories/CategoryRepository'
import { ref } from 'vue'

const props = withDefaults(defineProps<{
  category: CategoryIndex,
  hideActions?: boolean,
  color?: string,
}>(), {
  hideActions: false,
  color: '#FFEDD3'
})

const emits = defineEmits<{
  (e: 'click', event: MouseEvent): void,
  (e: 'deleted'): void,
  (e: 'edit', id: number): void
}>()

const showDeletePrompt = ref(false)

const destroyCategoryPrompt = () => {
  showDeletePrompt.value = true
}

const destroyCategory = async () => {
  await CategoryRepository.destroy(props.category.id)
  emits('deleted')
}
</script>

<style scoped lang="scss">
.dc {
  position: relative;
  border-radius: 10px;
  border: #0000000A 1px solid;
  aspect-ratio: 1.5;
  overflow: hidden;
  cursor: pointer;
  filter: drop-shadow(0px 0px 2px rgba(0, 0, 0, 0.12)) drop-shadow(0px 2px 2px rgba(0, 0, 0, 0.24));

  .dc__background {
    height: 100%;
    width: 100%;

    font-size: 26px;
    font-weight: 500;
    line-height: 39px;
    text-align: center;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
    text-overflow: ellipsis;
  }

  &:hover .dc__actions {
    display: flex;
  }

  .dc__actions {
    display: none;

    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    justify-content: flex-end;
  }
}
</style>
