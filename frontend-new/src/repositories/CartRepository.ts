import Repository from '../plugins/axios';
import type {AxiosPromise} from "axios";
import type {CartItemIndex} from "@/models/CartItem";

const base = 'cart';

export default {
  index(): AxiosPromise<CartItemIndex[]> {
    return Repository.get(base);
  },
  store(menu_id: number, amount: number, consider_fridge: boolean): AxiosPromise<CartItemIndex[]> {
    return Repository.post(base, {
      menu_id,
      amount,
      consider_fridge,
    });
  },
}
