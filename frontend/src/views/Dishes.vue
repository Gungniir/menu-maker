<template>
  <div class="dishes">
    <div class="dishes__header">
      Все блюда
    </div>
    <div class="dishes__container">
      <DishCard v-for="dish of dishes" :key="dish.id" :dish="dish" />
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import DishRepository from "@/repositories/DishRepository";
import {DishPaginate} from "@/models/Dish";
import DishCard from "@/components/DishCard.vue";

@Component({
  components: {DishCard}
})
export default class Dishes extends Vue {
  public dishes: DishPaginate[] = [];
  public pages = 1
  public page = 0

  mounted(): void {
    this.fetchMoreDishes();
  }

  async fetchMoreDishes(): Promise<void> {
    if (this.pages === this.page) {
      return;
    }

    const {data} = await DishRepository.paginate(this.page++);

    this.dishes.push(...data.data);
    this.pages = data.last_page;
  }
}
</script>

<style scoped lang="scss">
.dishes {
  width: 100%;
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
</style>
