<template>
  <dialog-card :title="title" v-model="opened" :mini="true">
    <template #activator="options">
      <slot name="activator" v-bind="options" />
    </template>
    <div class="dialog__text">{{ text }}</div>
    <div class="dialog__actions">
      <v-btn outlined color="primary" @click="opened = false">Отмена</v-btn>
      <v-btn class="ml-4" color="primary" @click="emits('confirm'); opened = false">Да</v-btn>
    </div>
  </dialog-card>
</template>

<script lang="ts" setup>
import DialogCard from "@/components/DialogCard.vue";
import { ref, watch } from 'vue'

const props = withDefaults(defineProps<{
  title: string,
  text: string,
  modelValue?: boolean,
}>(), {
  modelValue: false
})

const emits = defineEmits<{
  (e: 'confirm'),
  (e: 'input', value: boolean)
}>()

const opened = ref(props.modelValue)

watch(opened, (val) => emits('input', val))
watch(() => props.modelValue, (val) => opened.value = val)
</script>

<style scoped lang="scss">
.dialog__text {
  padding-bottom: 20px;
}

.dialog__actions {
  display: flex;
  justify-content: flex-end;
}
</style>
