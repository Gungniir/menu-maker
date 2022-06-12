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
          <template #activator="{ on, attrs }">
            <v-btn v-on="on" v-bind="attrs" icon @click="opened = false">
              <v-icon color="text">mdi-close</v-icon>
            </v-btn>
          </template>
          Закрыть
        </v-tooltip>
      </div>
      <div class="dialog__title">
        {{ title }}
      </div>

      <validation-observer ref="observer" v-if="withObserver !== false" tag="div" class="dialog__body" v-slot="{ invalid }">
        <slot :observer="$refs.observer" :invalid="invalid" />
      </validation-observer>
      <div v-else class="dialog__body">
        <slot />
      </div>
    </div>
  </v-dialog>
</template>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
import {ValidationObserver} from "vee-validate";

@Component({})
export default class DialogCard extends Vue {
  $refs!: {
    observer: InstanceType<typeof ValidationObserver>
  }

  @Prop({default: 'Заголовок окна'}) title!: string;
  @Prop({default: false}) value!: boolean;
  @Prop({default: false}) withObserver!: boolean;
  @Prop({default: false}) mini!: boolean;

  public get observer(): InstanceType<typeof ValidationObserver> {
    return this.$refs.observer;
  }

  public reset(): void {
    this.$nextTick(() => {
      this.observer.reset();
    })
  }

  private opened = false;

  mounted(): void {
    this.opened = this.value;
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
.dialog {
  position: relative;
  width: 100%;
  padding: 65px 130px;
  background: var(--v-background-base);

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
