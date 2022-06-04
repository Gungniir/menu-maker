import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {Pagination} from "@/models/common/Pagination";
import {Dish, DishEntity, DishPaginate, DishShow} from "@/models/Dish";

const base = 'dish';

export default {
  paginate(page = 1): AxiosPromise<Pagination<DishPaginate>> {
    return Repository.get(base + '?page=' + page);
  },
  show(id: number): AxiosPromise<Pagination<DishShow>> {
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
  }
}
