import type {BaseType} from "@/models/common/BaseType";

export type ToolEntity = {
  name: string,
  amount: number, // Количтсвео у создателя
  reusable: boolean // Можно ли применять одновременно при готовке разных блюд
}

export type Tool = BaseType & ToolEntity & {
  creator_id: number,
}

export type ToolIndex = Tool

export type ToolShow = Tool
