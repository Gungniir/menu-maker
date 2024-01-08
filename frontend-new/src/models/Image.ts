import type {BaseType} from "@/models/common/BaseType";

export type Image = BaseType & {
  creator_id: number,
  name: string,
  filename: string,
  url: string,
}
