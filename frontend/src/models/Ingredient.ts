import {BaseType} from "@/models/common/BaseType";

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
  amount: number,
  unit: IngredientUnit
}

export type Ingredient = BaseType & IngredientEntity & {
  creator_id: number
}

export type IngredientIndex = Ingredient

export type IngredientShow = Ingredient
