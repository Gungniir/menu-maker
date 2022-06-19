<template>
  <dialog-card title="Составить список продуктов" v-model="opened">
    <validation-observer tag="div" v-slot="{ invalid }">
      <div class="mt-4">
        <validation-provider slim rules="required|min:1|integer">
          <v-text-field class="hide-messages" v-model="amount" outlined label="Количество человек" persistent-placeholder />
        </validation-provider>
      </div>
      <div class="mt-4">
        <v-switch v-model="considerFridge" label="Учитывать холодильник" />
      </div>
      <div class="mt-4">
        <v-btn block color="primary" large :loading="loading" :disabled="invalid" @click="onContinueClick">Составить</v-btn>
      </div>
    </validation-observer>
  </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import {mixins} from "vue-class-component";
import OutsideClickMixin from "@/mixins/OutsideClickMixin";
import CartRepository from "@/repositories/CartRepository";

@Component({
  components: {DialogCard}
})
export default class MenuSchemeAddEditDialog extends mixins(OutsideClickMixin) {
  @Prop({default: false}) readonly value!: boolean;
  @Prop({required: true}) readonly menuId!: number;

  private opened = false;
  private loading = false;
  private considerFridge = false;
  private amount = 1;

  mounted(): void {
    this.opened = this.value;
    this.reset();
  }

  private reset(): void {
    this.amount = 1;
    this.considerFridge = false;
  }

  private onContinueClick(): void {
    this.createCart();
  }

  async createCart(): Promise<void> {
    this.loading = true;
    await CartRepository.store(this.menuId, this.amount, this.considerFridge);

    this.opened = false;
    this.loading = false;
    this.createdEvent();
  }

  @Emit('created')
  createdEvent(): boolean {
    return true;
  }

  @Watch('opened')
  onOpenedChanged(value: boolean): void {
    this.$emit('input', value);
  }

  @Watch('value')
  onValueChanged(value: boolean): void {
    this.opened = value;

    if (value) {
      this.reset();
    }
  }
}
</script>

<style scoped lang="scss">

</style>
