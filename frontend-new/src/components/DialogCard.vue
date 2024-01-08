<template>
  <v-dialog
    width="630"
    v-model="opened"
  >
    <template #activator="options">
      <slot name="activator" v-bind="options" />
    </template>
    <div class="dialog" :class="{mini}">
      <div class="dialog__close">
        <v-tooltip
          open-delay="300"
          bottom
        >
          <template #activator="{ attrs }">
            <v-btn v-bind="attrs" icon @click="opened = false">
              <v-icon color="text">mdi-close</v-icon>
            </v-btn>
          </template>
          Закрыть
        </v-tooltip>
      </div>
      <div class="dialog__title">
        {{ title }}
      </div>

      <div class="dialog__body">
        <slot />
      </div>
    </div>
  </v-dialog>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'

const props = withDefaults(defineProps<{
  title?: string,
  modelValue?: boolean,
  mini?: boolean,
}>(), {
  title: 'Заголовок окна',
  modelValue: false,
  mini: false,
})

const emits = defineEmits<{
  (e: 'input', value: boolean),
}>()

const opened = ref(props.modelValue)

watch(opened, (val) => emits('input', val))
watch(() => props.modelValue, (val) => opened.value = val)
</script>

<style scoped lang="scss">
.dialog {
  position: relative;
  width: 100%;
  padding: 65px 95px;
  background: rgb(var(--v-theme-background));

  &.mini {
    padding: 32px 65px;

    .dialog__close {
      right: 18px;
      top: 18px;
    }
  }

  .dialog__close {
    position: absolute;
    right: 36px;
    top: 36px;
  }

  .dialog__title {
    text-align: center;
    font-size: 24px;
    font-weight: 400;
    line-height: 29px;
    letter-spacing: 0;
  }

  .dialog__body {
    margin-top: 30px;
    display: flex;
    flex-direction: column;
  }
}
</style>
