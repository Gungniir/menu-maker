import {Image} from "@/models/Image";
import {BaseType} from "@/models/common/BaseType";
import {Category} from "@/models/Category";
import {RecipeItem} from "@/models/RecipeItem";
import {Preparation} from "@/models/Preparation";
import {Tool} from "@/models/Tool";

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
  categories: Category[],
  recipe_items: RecipeItem[],
  preparations: Preparation[],
  tools: Tool[]
}
