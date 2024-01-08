import { h } from 'vue'
import type { IconSet, IconProps } from 'vuetify'
import AddIcon from '@/components/icons/AddIcon.vue'
import DishesIcon from '@/components/icons/DishesIcon.vue'
import IngredientsIcon from '@/components/icons/IngredientsIcon.vue'
import MenuIcon from '@/components/icons/MenuIcon.vue'
import ProductsIcon from '@/components/icons/ProductsIcon.vue'

const customSvgNameToComponent: any = {
  AddIcon,
  DishesIcon,
  IngredientsIcon,
  MenuIcon,
  ProductsIcon,
}

const customSVGs: IconSet = {
  component: (props: IconProps) => h(customSvgNameToComponent[props.icon]),
}

export { customSVGs /* aliases */ }
