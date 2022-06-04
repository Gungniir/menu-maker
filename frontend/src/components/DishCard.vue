<template>
  <div class="dc">
    <v-img width="100%" height="100%" :src="dish.images[0]" />
    <div class="dc__name">
      {{ dish.name }}
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {DishPaginate} from "@/models/Dish";

@Component({})
export default class DishCard extends Vue {
  @Prop() readonly dish!: DishPaginate
}
</script>

<style scoped lang="scss">
.dc {
  position: relative;
  border-radius: 10px;
  border: #0000000A 1px solid;
  aspect-ratio: 1.5;
  overflow: hidden;

  .dc__name {
    position: absolute;
    bottom: 0;
    left: 0;

    height: 53px;
    width: 100%;

    display: flex;
    align-items: center;

    overflow: hidden;
    text-overflow: ellipsis;

    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0 0 10px 10px;
  }
}
</style>
