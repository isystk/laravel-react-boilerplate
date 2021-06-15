import { Action } from 'redux'

export type PartsAppAction = Action

export const SHOW_OVERLAY = 'SHOW_OVERLAY'
export const HIDE_OVERLAY = 'HIDE_OVERLAY'

export const SHOW_LOADING = 'SHOW_LOADING'
export const HIDE_LOADING = 'HIDE_LOADING'

export const showOverlay = () => ({
  type: SHOW_OVERLAY,
})

export const hideOverlay = () => ({
  type: HIDE_OVERLAY,
})

export const showLoading = () => ({
  type: SHOW_LOADING,
})

export const hideLoading = () => ({
  type: HIDE_LOADING,
})
