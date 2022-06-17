<template>
  <div class="dishes">
    <div class="dishes__wrapper">
      <div class="dishes__header d-flex justify-center">
        <v-skeleton-loader v-if="categoryId && !category" type="image" height="50" width="250"/>
        <template v-else-if="category">{{ category.name }}</template>
        <template v-else>Все блюда</template>
      </div>
      <div ref="intersectionObserverRoot" class="dishes__container">
        <template v-if="loading && dishes.length === 0">
          <v-skeleton-loader v-for="index in 5" :key="index" class="fluid" type="image" style="aspect-ratio: 1.5" />
        </template>
        <DishCard v-for="(dish, index) of dishes" :key="dish.id" :dish="dish" @click="$router.push(`/dishes/${dish.id}`)" @deleted="dishes.splice(index, 1)" />
        <div ref="intersectionObserver" class="dishes__intersection-observer"/>
      </div>
    </div>
    <v-tooltip
      open-delay="300"
      bottom
    >
      <template #activator="{ on, attrs }">
        <v-btn
          color="primary"
          fab
          fixed
          bottom
          right
          style="bottom: 50px; right: calc(13vw + 25px)"
          v-on="on"
          v-bind="attrs"
          @click="showAddDialog = true; newDishName = ''; $refs.addDialog.reset()"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить блюдо
    </v-tooltip>
    <dialog-card ref="addDialog" v-model="showAddDialog" with-observer title="Добавить блюдо" v-slot="{ invalid }">
      <v-row no-gutters>
        <v-col>
          <validation-provider slim vid="name" name="название" rules="required" v-slot="{ errors }">
            <v-text-field v-model="newDishName" outlined color="primary" label="Название" persistent-placeholder :error-messages="errors"/>
          </validation-provider>
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

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
import DishRepository from "@/repositories/DishRepository";
import {DishEntity, DishIndex} from "@/models/Dish";
import DishCard from "@/components/DishCard.vue";
import DialogCard from "@/components/DialogCard.vue";
import {Category} from "@/models/Category";
import CategoryRepository from "@/repositories/CategoryRepository";

@Component({
  components: {DialogCard, DishCard}
})
export default class Dishes extends Vue {
  @Prop({default: 0}) categoryId!: number;

  private dishes: DishIndex[] = [];
  private category: Category|null = null;
  private lastPage = 1;
  private page = 1;
  private loading = true;
  private intersectionObserver = new IntersectionObserver((entries) => {
    this.onIntersectionCallback(entries[0].isIntersecting);
  }, {
    root: this.$refs.intersectionObserverRoot as Element,
  })
  private showAddDialog = false;
  private newDishName = '';

  mounted(): void {
    this.fetchMoreDishes();
    this.intersectionObserver.observe(this.$refs.intersectionObserver as Element);

    if (this.categoryId) {
      this.loadCategory();
    }
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

  async storeDish(): Promise<void> {
    const {data} = await DishRepository.store({name: this.newDishName} as DishEntity);

    await this.$router.push(`/dishes/${data.id}/edit`)
  }

  async fetchMoreDishes(): Promise<void> {
    if (this.lastPage < this.page) {
      return;
    }
    this.loading = true;

    const {data} = await DishRepository.paginate(this.page++, this.categoryId ?? undefined);

    this.dishes.push(...data.data);
    this.lastPage = data.last_page;
    this.loading = false;
  }

  @Watch('categoryId')
  async loadCategory(): Promise<void> {
    const {data} = await CategoryRepository.show(this.categoryId);

    this.category = data;
  }
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
