import {BaseType} from "@/models/common/BaseType";

export type Category = BaseType & {
  creator_id: number,
  name: string,
  group: string
}

export type CategoryIndex = Category

export type CategoryShow = Category

export type CategoryEntity = {
  name: string,
  dishes: number[]
}
