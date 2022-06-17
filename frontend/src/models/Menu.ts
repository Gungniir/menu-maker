export type MenuEntity = {
  scheme_id: number,
  amount: number,
  categories: number[] | undefined,
  wishes: number[] | undefined,
  meal_categories: {
    meal_id: number,
    category_id: number,
  }[] | undefined
}
