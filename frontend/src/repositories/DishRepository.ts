import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {Pagination} from "@/models/common/Pagination";
import {Dish, DishEntity, DishIndex, DishShow} from "@/models/Dish";

const base = 'dish';

export default {
  paginate(page = 1): AxiosPromise<Pagination<DishIndex>> {
    return Repository.get(base + '?page=' + page);
  },
  show(id: number): AxiosPromise<DishShow> {
    return Repository.get(base + '/' + id);
  },
  store(dish: DishEntity): AxiosPromise<Dish> {
    return Repository.post(base, dish);
  },
  update(id: number, dish: DishEntity): AxiosPromise<Dish> {
    // todo: Добавить возможность добавления в архив. Другой метод?
    return Repository.put(base + '/' + id, dish);
  },
  destroy(id: number): AxiosPromise<boolean> {
    return Repository.delete(base + '/' + id);
  },
  storeIngredient(dish_id: number, ingredient_id: number, amount: number): AxiosPromise<DishShow> {
    return Repository.put(base + '/' + dish_id + '/ingredient/' + ingredient_id, {amount});
  },
  destroyIngredient(dish_id: number, ingredient_id: number): AxiosPromise<DishShow> {
    return Repository.delete(base + '/' + dish_id + '/ingredient/' + ingredient_id);
  },
  storeRecipe(dish_id: number, item: string): AxiosPromise<DishShow> {
    return Repository.post(base + '/' + dish_id + '/recipe', {item});
  },
  updateRecipe(dish_id: number, recipe_item_id: number, item: string): AxiosPromise<DishShow> {
    return Repository.put(base + '/' + dish_id + '/recipe/' + recipe_item_id, {item});
  },
  destroyRecipe(dish_id: number, recipe_item_id: number): AxiosPromise<DishShow> {
    return Repository.delete(base + '/' + dish_id + '/recipe/' + recipe_item_id);
  },
  attachImage(dish_id: number, image_id: number): AxiosPromise<DishShow> {
    return Repository.put(base + '/' + dish_id + '/image/' + image_id);
  },
  detachImage(dish_id: number, image_id: number): AxiosPromise<DishShow> {
    return Repository.delete(base + '/' + dish_id + '/image/' + image_id);
  },
}
