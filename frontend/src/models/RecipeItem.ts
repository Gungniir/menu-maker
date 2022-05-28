import {BaseType} from "@/models/common/BaseType";

export type RecipeItem = BaseType & {
  dish_id: number,
  item: string,
  order: number,
}
