<template>
  <v-alert
    class="notification"
    border="left"
    transition="slide-x-reverse-transition"
    elevation="5"
    colored-border
    :value="opened"
    :type="type"
  >
    {{ text }}
  </v-alert>
</template>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator'

@Component({})
export default class Notification extends Vue {
  private to = 0;

  get type(): string {
    return this.$store.state.notificationType;
  }
  get text(): string {
    return this.$store.state.notificationText;
  }
  get opened(): boolean {
    return this.$store.state.notificationShowed;
  }

  @Watch('opened')
  closeAfterDelay(value: boolean): void {
    if (!value) {
      clearTimeout(this.to);
      return;
    }

    this.to = setTimeout(() => {
      this.$store.state.notificationShowed = false;
    }, 10*1000);
  }
}
</script>

<style scoped lang="scss">
.notification {
  z-index: 10;
  position: fixed;
  right: 15px;
  bottom: 15px;
  max-width: 30vw;
  word-break: break-word;
}
</style>
