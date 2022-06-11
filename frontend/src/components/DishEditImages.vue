<template>
  <div class="images">
    <div class="images__image">
      <template v-if="images[0]">
        <v-img :src="selectedImage.url" height="100%" />
        <div class="images__image-actions">
          <v-tooltip
            bottom
            open-delay="300"
          >
            <template #activator="{ on, attrs }">
              <v-btn icon v-on="on" v-bind="attrs" @click="detachImage(selectedImage.id)">
                <v-icon>mdi-minus</v-icon>
              </v-btn>
            </template>
            Удалить
          </v-tooltip>
        </div>
      </template>
      <div v-else class="images__image-placeholder" />
    </div>
    <div class="images__images">
      <div v-for="image of images" :key="image.id" class="images__images-item">
        <v-img :src="image.url" height="100%" @click="selectedImageId = image.id" />
      </div>
      <div v-if="images.length < 7" class="images__images-item" v-ripple @click="$refs.imageAddInput.click()">
        <input ref="imageAddInput" type="file" style="display: none" @input="handleInputEvent" />
        <div class="images__image-placeholder">
          <v-icon>$add</v-icon>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue} from 'vue-property-decorator'
import {Image} from "@/models/Image";

@Component({})
export default class DishEditImages extends Vue {
  @Prop({required: true}) images!: Image[]
  @Prop({default: 0}) selectedImageId!: number

  get selectedImage(): Image | undefined {
    return this.images[this.selectedImageId];
  }

  private handleInputEvent(event: InputEvent): void {
    const target = event.target as HTMLInputElement;

    if (!target.files || !target.files[0]) {
      return;
    }

    this.storeImage(target.files[0]);
  }

  @Emit('detach-image')
  detachImage(id: number): number {
    return id;
  }

  @Emit('store-image')
  storeImage(file: File): File {
    return file;
  }
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