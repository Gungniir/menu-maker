import Repository from '../plugins/axios';
import type {AxiosPromise} from "axios";
import type {Pagination} from "@/models/common/Pagination";
import type {Image} from "@/models/Image";

const base = 'image';

export default {
  paginate(page = 1): AxiosPromise<Pagination<Image>> {
    return Repository.get(base + '?page=' + page);
  },
  show(id: number): AxiosPromise<string> {
    return Repository.get(base + '/' + id);
  },
  store(name: string, image: File): AxiosPromise<Image> {
    const form = new FormData();

    form.append('name', name)
    form.append('file', image);

    return Repository.post(base, form);
  },
  destroy(id: number): AxiosPromise<boolean> {
    return Repository.delete(base + '/' + id);
  },
}
