import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";
import {Pagination} from "@/models/common/Pagination";
import {Tool, ToolEntity, ToolIndex, ToolShow} from "@/models/Tool";

const base = 'tool';

export default {
  paginate(page = 1): AxiosPromise<Pagination<ToolIndex>> {
    return Repository.get(base + '?page=' + page);
  },
  show(id: number): AxiosPromise<ToolShow> {
    return Repository.get(base + '/' + id);
  },
  store(tool: ToolEntity): AxiosPromise<Tool> {
    return Repository.post(base, tool);
  },
  update(id: number, tool: ToolEntity): AxiosPromise<Tool> {
    return Repository.put(base + '/' + id, tool);
  },
  destroy(id: number): AxiosPromise<Tool> {
    return Repository.delete(base + '/' + id);
  }
}
