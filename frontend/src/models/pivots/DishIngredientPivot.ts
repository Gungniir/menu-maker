import {BaseType} from "@/models/common/BaseType";

export type DishIngredientPivot = BaseType & {
  dish_id: number,
  ingredient_id: number,
  amount: number
}
