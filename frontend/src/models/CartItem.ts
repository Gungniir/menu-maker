import {Ingredient} from "@/models/Ingredient";

type decimal = string;

export type CartItem = {
  id: number,
  ingredient_id: number,
  amount: decimal,
}

export type CartItemIndex = CartItem & {
  ingredient: Ingredient
}
