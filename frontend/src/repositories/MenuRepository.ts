import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {MenuSchemeEntity, MenuSchemeIndex, MenuSchemeShow} from "@/models/MenuScheme";
import {MenuEntity} from "@/models/Menu";

const base = 'menu';

export default {
  index(): AxiosPromise<MenuSchemeIndex[]> {
    return Repository.get(base);
  },
  show(id: number): AxiosPromise<MenuSchemeShow> {
    return Repository.get(base + '/' + id);
  },
  store(entity: MenuEntity): AxiosPromise<any> {
    return Repository.post(base, entity);
  },
  update(id: number, entity: MenuSchemeEntity): AxiosPromise<MenuSchemeShow> {
    return Repository.put(base + '/' + id, entity);
  },
  destroy(id: number): AxiosPromise<MenuSchemeShow> {
    return Repository.delete(base + '/' + id);
  },
}
