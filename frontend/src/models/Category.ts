import {BaseType} from "@/models/common/BaseType";
import {Dish} from "@/models/Dish";

export type Category = BaseType & {
  creator_id: number,
  name: string,
  group: string
}

export type CategoryIndex = Category

export type CategoryShow = Category & {
  dishes: Dish[],
}

export type CategoryEntity = {
  name: string,
  dishes: number[]
}
