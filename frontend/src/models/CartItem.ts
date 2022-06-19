import {Ingredient} from "@/models/Ingredient";

export type CartItem = {
  id: number,
  ingredient_id: number,
  amount: number,
}

export type CartItemIndex = CartItem & {
  ingredient: Ingredient
}
