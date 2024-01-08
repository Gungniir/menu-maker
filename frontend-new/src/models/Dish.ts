import type {Image} from "@/models/Image";
import type {BaseType} from "@/models/common/BaseType";
import type {Category} from "@/models/Category";
import type {RecipeItem} from "@/models/RecipeItem";
import type {Preparation} from "@/models/Preparation";
import type {Tool} from "@/models/Tool";
import type {IngredientWithDishPivot} from "@/models/Ingredient";

export type DishEntity = {
  name: string,
  is_archive: boolean,
  cooking_time: number | null,
  kcal: number | null,
  proteins: number | null,
  fats: number | null,
  carbohydrates: number | null,
  link: string | null,
}

export type Dish = BaseType & DishEntity & {
  creator_id: number,
}

export type DishIndex = Dish & {
  images: Image[],
  categories: Category[],
}

export type DishShow = Dish & {
  images: Image[],
  ingredients: IngredientWithDishPivot[],
  categories: Category[],
  recipe_items: RecipeItem[],
  preparations: Preparation[],
  tools: Tool[]
}
