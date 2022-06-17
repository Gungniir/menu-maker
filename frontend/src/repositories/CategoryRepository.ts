import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {Pagination} from "@/models/common/Pagination";
import {CategoryEntity, CategoryIndex, CategoryShow} from "@/models/Category";

const base = 'category';

export default {
  paginate(page = 1): AxiosPromise<Pagination<CategoryIndex>> {
    return Repository.get(base + '?page=' + page);
  },
  show(id: number): AxiosPromise<CategoryShow> {
    return Repository.get(base + '/' + id);
  },
  store(category: CategoryEntity): AxiosPromise<CategoryShow> {
    return Repository.post(base, category);
  },
  update(id: number, category: CategoryEntity): AxiosPromise<CategoryShow> {
    return Repository.put(base + '/' + id, category);
  },
  destroy(id: number): AxiosPromise<CategoryShow> {
    return Repository.delete(base + '/' + id);
  },
}
