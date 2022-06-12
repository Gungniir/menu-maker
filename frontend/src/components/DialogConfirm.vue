<template>
  <dialog-card :title="title" v-model="opened" :mini="true">
    <template #activator="options">
      <slot name="activator" v-bind="options" />
    </template>
    <div class="dialog__text">{{ text }}</div>
    <div class="dialog__actions">
      <v-btn outlined color="primary" @click="opened = false">Отмена</v-btn>
      <v-btn class="ml-4" color="primary" @click="confirmHandler(); opened = false">Да</v-btn>
    </div>
  </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";

@Component({
  components: {DialogCard}
})
export default class DialogConfirm extends Vue {
  @Prop({required: true}) title!: string;
  @Prop({required: true}) text!: string;
  @Prop({default: false}) value!: boolean;

  private opened = false;

  mounted(): void {
    this.opened = this.value;
  }

  @Emit('confirm')
  confirmHandler(): boolean {
    return true;
  }

  @Watch('opened')
  onOpenedChanged(value: boolean): void {
    this.$emit('input', value);
  }

  @Watch('value')
  onValueChanged(value: boolean): void {
    this.opened = value;
  }
}
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
