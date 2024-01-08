<template>
  <div class="images">
    <div class="images__image">
      <v-fade-transition>
        <div v-show="loading" class="images__image-loader">
          <v-progress-circular indeterminate color="primary" />
        </div>
      </v-fade-transition>
      <template v-if="sortedImages[0]">
        <v-img :src="selectedImage.url" height="100%" />
        <div v-if="editMode" class="images__image-actions">
          <v-tooltip
            bottom
            open-delay="300"
          >
            <template #activator="{ attrs }">
              <v-btn icon v-bind="attrs" @click="emits('detach-image', selectedImageId)">
                <v-icon color="text">mdi-delete</v-icon>
              </v-btn>
            </template>
            Удалить
          </v-tooltip>
        </div>
      </template>
      <div v-else class="images__image-placeholder" />
    </div>
    <div class="images__images">
      <div v-for="image of sortedImages" :key="image.id" class="images__images-item">
        <v-img :src="image.url" height="100%" @click="selectedImageId = image.id" />
      </div>
      <div
        v-if="editMode && images.length < 7"
        v-ripple
        class="images__images-item"
        @click="imageAddInput.click()"
      >
        <input ref="imageAddInput" type="file" style="display: none" @input="handleInputEvent" />
        <div class="images__image-placeholder">
          <v-icon>custom:AddIcon</v-icon>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { Image } from '@/models/Image'
import { computed, ref } from 'vue'

const props = withDefaults(defineProps<{
  images: Image[],
  editMode?: boolean,
  loading?: boolean
}>(), {
  editMode: false,
  loading: true,
})

const emits = defineEmits<{
  (e: 'detach-image', id: number),
  (e: 'store-image', file: File),
}>()

const selectedImageId = ref(0)

const imageAddInput = ref(null)

const sortedImages = computed(() => {
  const result = props.images.slice()

  result.sort((a, b) => a.id - b.id)

  return result
})

const selectedImage = computed(() => {
  const image = props.images.find(({ id }) => id === selectedImageId.value)

  return image ?? sortedImages.value[0]
})

const handleInputEvent = (event: InputEvent) => {
  const target = event.target as HTMLInputElement

  if (!target.files || !target.files[0]) {
    return
  }

  emits('store-image', target.files[0])
}
</script>

<style scoped lang="scss">
.images {
  display: flex;
  flex-direction: column;

  .images__image {
    position: relative;
    border-radius: 25px;
    overflow: hidden;
    aspect-ratio: 1.5;

    .images__image-loader {
      z-index: 1;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #00000030;
    }

    .images__image-actions {
      position: absolute;
      top: 10px;
      right: 10px;
    }
  }

  .images__images {
    margin-top: 12px;
    display: flex;

    .images__images-item {
      width: 12.5%;
      overflow: hidden;
      border-radius: 5px;
      aspect-ratio: 1;
    }

    .images__images-item + .images__images-item {
      margin-left: 2.08%;
    }
  }

  .images__image-placeholder {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #FFEDD3;
  }
}
</style>
