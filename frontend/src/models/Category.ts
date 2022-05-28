import {BaseType} from "@/models/common/BaseType";

export type Category = BaseType & {
  creator_id: number,
  name: string,
  group: string
}
