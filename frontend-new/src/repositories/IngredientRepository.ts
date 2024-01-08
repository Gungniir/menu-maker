import Repository from '../plugins/axios';
import type {AxiosPromise} from "axios";
import type {Pagination} from "@/models/common/Pagination";
import type {Ingredient, IngredientEntity, IngredientIndex, IngredientShow} from "@/models/Ingredient";

const base = 'ingredient';

export default {
  paginate(page = 1): AxiosPromise<Pagination<IngredientIndex>> {
    return Repository.get(base + '?page=' + page);
  },
  types(): AxiosPromise<string[]> {
    return Repository.get(base + '/types');
  },
  show(id: number): AxiosPromise<IngredientShow> {
    return Repository.get(base + '/' + id);
  },
  store(ingredient: IngredientEntity): AxiosPromise<Ingredient> {
    return Repository.post(base, ingredient);
  },
  update(id: number, ingredient: IngredientEntity): AxiosPromise<Ingredient> {
    return Repository.put(base + '/' + id, ingredient);
  },
  destroy(id: number): AxiosPromise<Ingredient> {
    return Repository.delete(base + '/' + id);
  }
}
