import { LOCALSTORAGE_KEYS } from '@/constants'
import { isBrowser } from '@/utils/general'

/**
 * Local Storage へ値を保存します。
 *
 */
export const storeStorage = (key: LOCALSTORAGE_KEYS, value = {}) => {
  if (!isBrowser()) {
    return
  }
  localStorage.setItem(key, JSON.stringify(value))
}

/**
 * Local Storage から値を取得します。
 *
 */
export const getStorage = (key: LOCALSTORAGE_KEYS) => {
  if (!isBrowser()) {
    return null
  }
  const value = localStorage.getItem(key)
  if (!value) {
    return null
  }
  return JSON.parse(value)
}

/**
 * Local Storage から値を削除します。
 *
 */
export const removeStorage = (key: LOCALSTORAGE_KEYS) => {
  if (!isBrowser()) {
    return
  }
  localStorage.removeItem(key)
}
