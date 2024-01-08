import { clone } from 'lodash-es'

let registeredClasses: {
  nodeClass: string,
  allowMenu: boolean,
  callback: () => void,
}[] = []

const register = (nodeClass: string, callback: () => void, allowVMenu = false) => {
  setTimeout(() => {
    this.ocRegisteredClasses.push({
      nodeClass,
      allowMenu: allowVMenu,
      callback,
    })
  }, 100)
}

const detect = (event: MouseEvent) => {
  let target = event.target as HTMLElement
  let check = clone(registeredClasses)

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
        check = check.filter(({ nodeClass }) => nodeClass !== item.nodeClass)
      }
    }

    target = target.parentNode as HTMLDivElement
  } while (target && target.tagName !== 'BODY')

  for (const item of check) {
    registeredClasses = registeredClasses.filter(({ nodeClass }) => nodeClass !== item.nodeClass)

    // outside click
    if (typeof item.callback === 'function') {
      item.callback()
    }
  }
}
const drop = (nodeClass: string) => {
  registeredClasses = registeredClasses.filter(({ nodeClass: a }) => a !== nodeClass)
}

let booted = false
const useOutsideClickHandler = () => {
  if (!booted) {
    document.addEventListener('mouseup', detect)
    booted = true
  }

  return {
    registerOC: register,
    dropOC: drop,
  }
}

export default useOutsideClickHandler
