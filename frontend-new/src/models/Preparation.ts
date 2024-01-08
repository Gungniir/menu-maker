import type {BaseType} from "@/models/common/BaseType";

export type Preparation = BaseType & {
  creator_id: number,
  dish_id: number | null, // Если подготовка - готовка блюда
  name: string | null, // Если подготовка - не готовка блюда
  preparation_time: string | null, // Если подготовка - не готовка блюда (в минутах)
}
