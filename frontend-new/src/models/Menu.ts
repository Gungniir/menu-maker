import type {BaseType} from "@/models/common/BaseType";
import type {MenuItem} from "@/models/MenuItem";
import type {Dish} from "@/models/Dish";
import type {Image} from "@/models/Image";
import type {MenuMeal} from "@/models/MenuMeal";

export type Menu = BaseType & {
  user_id: number,
  start_date: string, // YYYY-MM-DD
  amount: number,
}

export type MenuShow = Menu & {
  meals: (MenuMeal & {
    items: (MenuItem & {
      dish: Dish & {
        images: Image[]
      }
    })[]
  })[]
}

export type MenuEntity = {
  scheme_id: number,
  start_date: string,
  amount: number,
  categories: number[] | undefined,
  wishes: number[] | undefined,
  meal_categories: {
    meal_id: number,
    category_id: number,
  }[] | undefined
}
