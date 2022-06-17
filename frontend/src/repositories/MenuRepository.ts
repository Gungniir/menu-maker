import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {MenuEntity, MenuShow} from "@/models/Menu";

const base = 'menu';

export default {
  index(): AxiosPromise<MenuShow[]> {
    return Repository.get(base);
  },
  show(id: number): AxiosPromise<MenuShow> {
    return Repository.get(base + '/' + id);
  },
  showForDate(date: string): AxiosPromise<MenuShow> {
    return Repository.get(base + '/date/' + date);
  },
  store(entity: MenuEntity): AxiosPromise<MenuShow> {
    return Repository.post(base, entity);
  },
}
