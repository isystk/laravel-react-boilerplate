import { Action } from 'redux'

export type PartsAppAction = Action

export const SHOW_OVERLAY = 'SHOW_OVERLAY'
export const HIDE_OVERLAY = 'HIDE_OVERLAY'

export const showOverlay = () => ({
  type: SHOW_OVERLAY,
})

export const hideOverlay = () => ({
  type: HIDE_OVERLAY,
})
