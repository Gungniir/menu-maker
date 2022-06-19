import Repository from '../plugins/axios';
import {AxiosPromise} from "axios";

const base = 'cart';

export default {
  store(menu_id: number, amount: number, consider_fridge: boolean): AxiosPromise<void> {
    return Repository.post(base, {
      menu_id,
      amount,
      consider_fridge,
    });
  },
}
