<template>
  <div class="dishes">
    <div class="dishes__wrapper">
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
          <CategoryCard v-for="(category, index) of categories" :key="category.id" :category="category" @click="$router.push(`/categories/${category.id}`)" @deleted="categories.splice(index, 1)" />
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
          @click="showAddDialog = true; newCategoryName = ''; $refs.addDialog.reset()"
        >
          <v-icon color="white">mdi-plus</v-icon>
        </v-btn>
      </template>
      Добавить категорию
    </v-tooltip>
    <dialog-card ref="addDialog" v-model="showAddDialog" with-observer title="Добавить категорию" v-slot="{ invalid }">
      <v-row no-gutters>
        <v-col>
          <validation-provider slim vid="name" name="название" rules="required" v-slot="{ errors }">
            <v-text-field v-model="newCategoryName" outlined color="primary" label="Название" persistent-placeholder :error-messages="errors"/>
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
import {Component, Vue} from 'vue-property-decorator'
import DishCard from "@/components/DishCard.vue";
import DialogCard from "@/components/DialogCard.vue";
import {CategoryIndex} from "@/models/Category";
import CategoryCard from "@/components/CategoryCard.vue";
import CategoryRepository from "@/repositories/CategoryRepository";

@Component({
  components: {CategoryCard, DialogCard, DishCard}
})
export default class Categories extends Vue {
  private fakeCategory = {
    name: 'Все блюда'
  } as CategoryIndex
  private categories: CategoryIndex[] = [];
  private lastPage = 1;
  private page = 1;
  private loading = true;
  private intersectionObserver = new IntersectionObserver((entries) => {
    this.onIntersectionCallback(entries[0].isIntersecting);
  }, {
    root: this.$refs.intersectionObserverRoot as Element,
  })
  private showAddDialog = false;
  private newCategoryName = '';

  mounted(): void {
    this.fetchMoreCategories();
    this.intersectionObserver.observe(this.$refs.intersectionObserver as Element);
  }

  beforeDestroy(): void {
    this.intersectionObserver.unobserve(this.$refs.intersectionObserver as Element);
  }

  onIntersectionCallback(visible: boolean): void {
    if (this.loading || !visible) {
      return;
    }

    this.fetchMoreCategories();
  }

  async storeDish(): Promise<void> {
    const {data} = await CategoryRepository.store({
      name: this.newCategoryName,
      dishes: []
    });

    if (this.page > this.lastPage) {
      this.categories.push(data);
    }

    this.showAddDialog = false;
    this.newCategoryName = '';
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
