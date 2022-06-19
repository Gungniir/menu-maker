<template>
  <div class="products">
    <div class="products__header">
      <div class="products__header-wrapper">
        <div class="products__header-text">
          Список продуктов
        </div>
      </div>
    </div>
    <div class="products__container">
      <div v-if="loading" class="products__category">
        <div class="products__category-header">
          <v-skeleton-loader class="fluid" type="text" height="24" width="200"/>
        </div>
        <ul style="padding-left: 16px;">
          <li v-for="index in 7" :key="index" class="products__category-item mb-1" style="list-style: none">
            <v-skeleton-loader class="fluid" type="text" height="18" width="150" />
          </li>
        </ul>
      </div>
      <div v-else class="products__category">
        <div class="products__category-header">
          Без категории
        </div>
        <ul style="padding-left: 16px;">
          <li v-for="item of filteredItems" :key="item.id" class="products__category-item">
            {{ item.ingredient.name }} - {{ item.amount }} {{ item.ingredient.unit }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import CartRepository from "@/repositories/CartRepository";
import {CartItemIndex} from "@/models/CartItem";

@Component({})
export default class Products extends Vue {
  private loading = false;
  private items: CartItemIndex[] = [];

  get sortedItems(): CartItemIndex[] {
    const result = this.items.slice();

    result.sort((a, b) => a.ingredient.name.localeCompare(b.ingredient.name));

    return result;
  }

  get filteredItems(): CartItemIndex[] {
    return this.sortedItems;
  }

  mounted(): void {
    this.loadProducts();
  }

  async loadProducts(): Promise<void> {
    this.loading = true;

    const {data} = await CartRepository.index();
    this.items = data;

    this.loading = false;
  }
}
</script>

<style scoped lang="scss">
.products {
  position: relative;
  height: 100%;
  overflow: hidden;

  .products__header {
    padding: 40px;
    display: flex;
    justify-content: center;

    .products__header-wrapper {
      display: flex;
      align-items: center;
      width: fit-content;

      .products__header-text {
        font-size: 36px;
        font-weight: 400;
        line-height: 44px;
        letter-spacing: 0;
        text-align: center;
      }
    }
  }

  .products__container {
    .products__category {
      border-top: 0.7px solid rgba(0, 0, 0, 0.15);
      padding: 15px 70px;

      &:last-child {
        border-bottom: 0.7px solid rgba(0, 0, 0, 0.15);;
      }

      .products__category-header {
        font-size: 20px;
        font-weight: 500;
        line-height: 24px;
        letter-spacing: 0;
        text-align: left;

        margin-bottom: 15px;
      }
    }

  }
}
</style>
