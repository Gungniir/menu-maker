/**
 * В общем, у нас есть две главные функции
 *
 * ocRegister -- регистрируем класс, и выполняем callback, если клик был за пределами этого класса (выполняется один раз)
 * ocDrop -- сбросить всё
 *
 * p.s. oc - outside click
 *
 * Пример использования: EditTaskModal.vue [319-332]
 */

import {Component, Vue} from "vue-property-decorator";

@Component
export default class OutsideClickMixin extends Vue {
  private ocRegisteredClasses: {
    nodeClass: string,
    allowMenu: boolean,
    callback: () => void,
  }[] = [];

  mounted(): void {
    this.ocStart();
  }

  destroyed(): void {
    this.ocStop();
  }

  ocStart(): void {
    document.body.addEventListener('click', this.ocDetect);
  }
  ocStop(): void {
    document.body.removeEventListener('click', this.ocDetect);
  }

  ocRegister(nodeClass: string, callback: () => void, allowVMenu = false): void {
    setTimeout(() => {
      this.ocRegisteredClasses.push({
        nodeClass,
        allowMenu: allowVMenu,
        callback
      })
    }, 100);
  }
  ocDetect(event: MouseEvent): void {

    // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
    let target = event.target as HTMLDivElement;
    let check = this.ocRegisteredClasses.map((item) => ({
      ...item
    }));

    do {
      for (const item of check) {
        if (target.classList && (target.classList.contains(item.nodeClass) || item.allowMenu && (
          target.classList.contains('v-list-item') ||
          target.classList.contains('v-list-item__title') ||
          target.classList.contains('v-list-item__content') ||
          target.classList.contains('v-list') ||
          target.classList.contains('v-menu__content')
        ))) {
          // not outside click!
          check = check.filter(({nodeClass}) => nodeClass !== item.nodeClass);
        }
      }

      target = target.parentNode as HTMLDivElement;
    } while (target && target.tagName !== 'BODY');

    for (const item of check) {
      this.ocRegisteredClasses = this.ocRegisteredClasses.filter(({nodeClass}) => nodeClass !== item.nodeClass);

      // outside click
      if (typeof item.callback === 'function') {
        item.callback();
      }
    }
  }
  ocDrop(nodeClass: string): void {
    this.ocRegisteredClasses = this.ocRegisteredClasses.filter(({nodeClass: a}) => a !== nodeClass);
  }
}
