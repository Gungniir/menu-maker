import {BaseType} from "@/models/common/BaseType";

export type Tool = BaseType & {
  creator_id: number,
  name: string,
  amount: number, // Количтсвео у создателя
  reusable: boolean // Можно ли применять одновременно при готовке разных блюд
}
