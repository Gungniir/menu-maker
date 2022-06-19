<template>
  <div class="dishes">
    <div class="dishes__wrapper">
      <div class="dishes__header d-flex justify-center">
        Категории
      </div>
      <div ref="intersectionObserverRoot" class="dishes__container">
        <template v-if="loading && categories.length === 0">
          <v-skeleton-loader class="fluid" type="image" style="aspect-ratio: 1.5" />
          <div/>
          <div/>
          <v-skeleton-loader v-for="index in 5" :key="index" class="fluid" type="image" style="aspect-ratio: 1.5" />
        </template>
        <template v-else>
          <CategoryCard hide-actions :category="fakeCategory" @click="$router.push('/dishes')"/>
          <div/>
          <div/>
          <CategoryCard
            v-for="(category, index) of categories"
            :key="category.id"
            :category="category"
            :color="colors[index + 2 % colors.length]"
            @click="$router.push(`/categories/${category.id}`)"
            @deleted="categories.splice(index, 1)"
            @edit="selectedCategoryId = category.id; showAddEditDialog = true"
          />
        </template>
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
          @click="selectedCategoryId = 0; showAddEditDialog = true"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить категорию
    </v-tooltip>
    <category-add-edit-dialog v-model="showAddEditDialog" :category-id="selectedCategoryId" @created="onCreatedEvent" @updated="onUpdatedEvent"/>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import DishCard from "@/components/DishCard.vue";
import DialogCard from "@/components/DialogCard.vue";
import {CategoryIndex, CategoryShow} from "@/models/Category";
import CategoryCard from "@/components/CategoryCard.vue";
import CategoryRepository from "@/repositories/CategoryRepository";
import CategoryAddEditDialog from "@/components/CategoryAddEditDialog.vue";

@Component({
  components: {CategoryAddEditDialog, CategoryCard, DialogCard, DishCard}
})
export default class Categories extends Vue {
  private lastPage = 1;
  private page = 1;
  private loading = true;
  private colors = ['#CAFCDE', '#FCE8CA', '#E5E5E5', '#FCCACA', '#D8EFFF', '#FBFCCA', '#F9F9F9', '#ECCAFC', '#CBFCCA'];

  private fakeCategory = {
    name: 'Все блюда'
  } as CategoryIndex
  private categories: CategoryIndex[] = [];

  private intersectionObserver = new IntersectionObserver((entries) => {
    this.onIntersectionCallback(entries[0].isIntersecting);
  }, {
    root: this.$refs.intersectionObserverRoot as Element,
  })

  private showAddEditDialog = false;
  private selectedCategoryId = 0;

  mounted(): void {
    this.fetchMoreCategories();
    this.intersectionObserver.observe(this.$refs.intersectionObserver as Element);
  }

  beforeDestroy(): void {
    this.intersectionObserver.unobserve(this.$refs.intersectionObserver as Element);
  }

  onCreatedEvent(category: CategoryShow): void {
    if (this.page > this.lastPage) {
      this.categories.push(category);
    }
  }

  onUpdatedEvent(category: CategoryShow): void {
    const index = this.categories.findIndex((c) => c.id === category.id);

    if (index === -1) return;

    this.categories.splice(index, 1, category);
  }

  onIntersectionCallback(visible: boolean): void {
    if (this.loading || !visible) {
      return;
    }

    this.fetchMoreCategories();
  }

  async fetchMoreCategories(): Promise<void> {
    if (this.lastPage < this.page) {
      return;
    }
    this.loading = true;

    const {data} = await CategoryRepository.paginate(this.page++);

    this.categories.push(...data.data);
    this.lastPage = data.last_page;
    this.loading = false;
  }

  delay(n: number): Promise<void> {
    return new Promise(function(resolve){
      setTimeout(resolve,n);
    });
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
