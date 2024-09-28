import { isBrowser } from '@/utils/general'

/**
 * Notification API を利用するための許可をブラウザに求める。
 *
 */
export const requestPermission = () => {
  if (!isBrowser()) {
    return
  }
  Notification.requestPermission().then((permission) => {
    if (permission == 'granted') {
      // 許可
    } else if (permission == 'denied') {
      // 拒否
    } else if (permission == 'default') {
      // 無視
    }
  })
}

/**
 * ブラウザに通知を表示します。
 *
 */
export const showNotification = (title, body, icon, tag = '', data = {}) => {
  if (!isBrowser()) {
    return null
  }
  const n = new Notification(title, {
    body,
    icon,
    tag,
    data,
  })
  // 確実に通知を閉じる
  setTimeout(n.close.bind(n), 5000)
}
