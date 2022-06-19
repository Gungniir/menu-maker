<template>
  <div class="recipe">
    <div class="recipe__header">
      Способ приготовления:
    </div>

    <div v-for="(recipeItem, index) of sortedRecipeItems" :key="recipeItem.id" class="recipe__item">
      <div class="recipe__item-index">
        {{ index + 1 }}
      </div>
      <div class="recipe__item-name">
        {{ recipeItem.item }}
      </div>
      <div v-if="editMode" class="recipe__item-actions">
        <v-tooltip
          bottom
          open-delay="300"
        >
          <template #activator="{ on, attrs }">
            <v-btn
              icon
              v-on="on"
              v-bind="attrs"
              @click="
                addRecipeItemId = recipeItem.id;
                addRecipeItemValue = recipeItem.item;
                addRecipeItemShow = true;
                $nextTick(() => $refs.addRecipeItemInput.focus())
              "
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          Изменить
        </v-tooltip>
        <v-tooltip
          bottom
          open-delay="300"
        >
          <template #activator="{ on, attrs }">
            <v-btn
              icon
              v-on="on"
              v-bind="attrs"
              @click="destroyRecipeItem(recipeItem.id)"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          Удалить
        </v-tooltip>
      </div>
    </div>
    <template v-if="editMode">
      <validation-observer ref="observer" v-if="addRecipeItemShow" slim v-slot="{ invalid }">
        <div class="recipe__item-add">
          <validation-provider rules="required" slim v-slot="{ errors }">
            <v-text-field
              v-model="addRecipeItemValue"
              ref="addRecipeItemInput"
              :prefix="addEditRecipeItemPrefix"
              :error-messages="errors"
              @focus="ocRegister('recipe__item-add', () => {
                      closeAddRecipeItem();
                    })"
              @keyup.enter="invalid ? closeAddRecipeItem() : addOrUpdateRecipeItem(); ocDrop('recipe__item-add')"
            />
          </validation-provider>
          <v-btn
            class="ml-4"
            color="primary"
            :outlined="invalid"
            @click="invalid ? closeAddRecipeItem() : addOrUpdateRecipeItem(); ocDrop('recipe__item-add')"
          >
            {{ invalid ? 'Отмена' : addRecipeItemId ? 'Изменить' : 'Добавить' }}
          </v-btn>
        </div>
      </validation-observer>
      <v-btn v-else icon @click="addRecipeItemShow = true; $nextTick(() => $refs.addRecipeItemInput.focus())">
        <v-icon>$add</v-icon>
      </v-btn>
    </template>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop} from 'vue-property-decorator'
import {RecipeItem} from "@/models/RecipeItem";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import {ValidationObserver} from "vee-validate";

@Component({})
export default class DishEditRecipe extends mixins(OutsideClickMixin) {
  $refs! : {
    observer: InstanceType<typeof ValidationObserver>
  }

  @Prop({required: true}) recipe_items!: RecipeItem[];
  @Prop({default: false}) editMode!: boolean;

  private addRecipeItemId = 0;
  private addRecipeItemValue = '';
  private addRecipeItemShow = false;

  private closeAddRecipeItem(): void {
    this.addRecipeItemShow = false;
    this.addRecipeItemValue = '';
    this.addRecipeItemId = 0;
  }

  private addOrUpdateRecipeItem(): void {
    if (this.addRecipeItemId) {
      this.updateRecipeItem();
      this.closeAddRecipeItem();
    } else {
      this.storeRecipeItem();
      this.addRecipeItemValue = '';
      this.addRecipeItemId = 0;
      this.$refs.observer.reset();
    }
  }

  get addEditRecipeItemPrefix(): string {
    return (this.addRecipeItemId ? this.sortedRecipeItems.findIndex(({id}) => id === this.addRecipeItemId) + 1 : this.recipe_items.length + 1).toString();
  }

  get sortedRecipeItems(): RecipeItem[] {
    const ri = this.recipe_items.slice();
    ri.sort(({id: id_a}, {id: id_b}) => id_a - id_b);

    return ri;
  }

  @Emit('update-recipe-item')
  updateRecipeItem(): {id: number, value: string} {
    return {
      id: this.addRecipeItemId,
      value: this.addRecipeItemValue
    }
  }

  @Emit('store-recipe-item')
  storeRecipeItem(): string {
    return this.addRecipeItemValue;
  }

  @Emit('destroy-recipe-item')
  destroyRecipeItem(id: number): number {
    return id;
  }
}
</script>

<style scoped lang="scss">


.recipe__header {
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: 600;
  line-height: 29px;
  letter-spacing: 0;
  text-align: left;
}

.recipe__item {
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center;
  min-height: 80px;
  padding-left: 40px;
  padding-top: 30px;
  padding-bottom: 10px;

  border-top: 1px solid rgba(0, 0, 0, 0.15);

  &:last-of-type {
    border-bottom: 1px solid rgba(0, 0, 0, 0.15);
  }

  &:hover {
    .recipe__item-actions {
      display: flex;
    }
  }

  .recipe__item-index {
    position: absolute;
    top: 10px;
    left: 10px;

    font-size: 24px;
    font-weight: 600;
    line-height: 29px;
    letter-spacing: 0;
    text-align: left;
  }

  .recipe__item-actions {
    margin-right: 12px;
    display: none;
  }
}

.recipe__item-add {
  display: flex;
  align-items: center;
}
</style>
