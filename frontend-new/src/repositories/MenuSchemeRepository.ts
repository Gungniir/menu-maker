import Repository from '../plugins/axios';
import type {AxiosPromise} from "axios";
import type {MenuSchemeEntity, MenuSchemeIndex, MenuSchemeShow} from "@/models/MenuScheme";

const base = 'menu_scheme';

export default {
  index(): AxiosPromise<MenuSchemeIndex[]> {
    return Repository.get(base);
  },
  show(id: number): AxiosPromise<MenuSchemeShow> {
    return Repository.get(base + '/' + id);
  },
  store(entity: MenuSchemeEntity): AxiosPromise<MenuSchemeShow> {
    return Repository.post(base, entity);
  },
  update(id: number, entity: MenuSchemeEntity): AxiosPromise<MenuSchemeShow> {
    return Repository.put(base + '/' + id, entity);
  },
  destroy(id: number): AxiosPromise<MenuSchemeShow> {
    return Repository.delete(base + '/' + id);
  },
}
