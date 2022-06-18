import {BaseType} from "@/models/common/BaseType";
import {MenuSchemeMeal} from "@/models/MenuSchemeMeal";
import {MenuSchemeRepeatGroup} from "@/models/MenuSchemeRepeatGroup";
import {MenuSchemeRepeatItem} from "@/models/MenuSchemeRepeatItem";

export type MenuScheme = BaseType & {
  user_id: number,
  name: string,
}

export type MenuSchemeShow = MenuScheme & {
  meals: MenuSchemeMeal[],
  groups: MenuSchemeRepeatGroup & {
    items: MenuSchemeRepeatItem[]
  }[],
}

export type MenuSchemeIndex = MenuScheme & {
  meals: MenuSchemeMeal[]
};

export type MenuSchemeEntity = {
  name: string,
  meals: string[],
  groups: {
    meal: string,
    day: number
  }[][]
}
