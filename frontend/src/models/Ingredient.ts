import {BaseType} from "@/models/common/BaseType";
import {DishIngredientPivot} from "@/models/pivots/DishIngredientPivot";

type decimal = string;

export enum IngredientUnit {
  Kilograms = 'кг',
  Grams = 'г',
  Liters = 'л',
  Milliliters = 'мл',
  Pieces = 'шт'
}

export type IngredientEntity = {
  name: string,
  is_perishable: boolean,
  type: string | null,
  amount: decimal,
  unit: IngredientUnit
}

export type Ingredient = BaseType & IngredientEntity & {
  creator_id: number
}

export type IngredientWithDishPivot = Ingredient & {
  pivot: DishIngredientPivot
}

export type IngredientIndex = Ingredient

export type IngredientShow = Ingredient
