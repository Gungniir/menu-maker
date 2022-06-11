<template>
  <div class="dc">
    <v-img v-if="dish.images[0]" width="100%" height="100%" :src="dish.images[0].url" />
    <div v-else class="dc__background"/>
    <div class="dc__actions">
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on" :to="`/dishes/${dish.id}/edit`">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>
        Редактировать
      </v-tooltip>
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
        Удалить
      </v-tooltip>
    </div>
    <div class="dc__name">
      {{ dish.name }}
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator'
import {DishIndex} from "@/models/Dish";

@Component({})
export default class DishCard extends Vue {
  @Prop() readonly dish!: DishIndex
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
    justify-content: center;

    overflow: hidden;
    text-overflow: ellipsis;

    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0 0 10px 10px;
  }

  .dc__background {
    height: 100%;
    width: 100%;

    background: #FFEDD3;
  }

  &:hover .dc__actions{
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
