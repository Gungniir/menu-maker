<template>
  <div class="dishes">
    <div class="dishes__header">
      Все блюда
    </div>
    <div ref="intersectionObserverRoot" class="dishes__container">
      <DishCard v-for="(dish, index) of dishes" :key="dish.id" :dish="dish" @click="$router.push(`/dishes/${dish.id}`)" @deleted="dishes.splice(index, 1)" />
      <div ref="intersectionObserver" class="dishes__intersection-observer"/>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import DishRepository from "@/repositories/DishRepository";
import {DishIndex} from "@/models/Dish";
import DishCard from "@/components/DishCard.vue";

@Component({
  components: {DishCard}
})
export default class Dishes extends Vue {
  private dishes: DishIndex[] = [];
  private lastPage = 1;
  private page = 1;
  private loading = true;
  private intersectionObserver = new IntersectionObserver((entries) => {
    this.onIntersectionCallback(entries[0].isIntersecting);
  }, {
    root: this.$refs.intersectionObserverRoot as Element,
  })

  mounted(): void {
    this.fetchMoreDishes();
    this.intersectionObserver.observe(this.$refs.intersectionObserver as Element);
  }

  beforeDestroy(): void {
    this.intersectionObserver.unobserve(this.$refs.intersectionObserver as Element);
  }

  onIntersectionCallback(visible: boolean): void {
    if (this.loading || !visible) {
      return;
    }

    this.fetchMoreDishes();
  }

  async fetchMoreDishes(): Promise<void> {
    if (this.lastPage < this.page) {
      return;
    }
    this.loading = true;

    const {data} = await DishRepository.paginate(this.page++);

    this.dishes.push(...data.data);
    this.lastPage = data.last_page;
    this.loading = false;
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
