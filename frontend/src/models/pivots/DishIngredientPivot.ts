import {BaseType} from "@/models/common/BaseType";

type decimal = string;

export type DishIngredientPivot = BaseType & {
  dish_id: number,
  ingredient_id: number,
  amount: decimal,
}
