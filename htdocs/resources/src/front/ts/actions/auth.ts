import { Action } from 'redux'
import { Auth } from '../store/StoreTypes'

/**
 * APIで返却されるデータ型を定義
 */
export interface AuthAppAction extends Action {
  payload: Auth
}

export const SET_SESSION = 'SET_SESSION'
export const SET_NAME = 'SET_NAME'
export const SET_EMAIL = 'SET_EMAIL'
export const SET_REMEMBER = 'SET_REMEMBER'
export const SET_CSRF = 'SET_CSRF'
export const SET_PARAMS = 'SET_PARAMS'

export const setSession = (session: any) => ({
  type: SET_SESSION,
  payload: {
    id: session.id,
    name: session.name,
  },
})

export const setName = (name: string) => ({
  type: SET_NAME,
  payload: {
    name,
  },
})

export const setEmail = (email: string) => ({
  type: SET_EMAIL,
  payload: {
    email,
  },
})

export const setRemember = (remember: string) => ({
  type: SET_REMEMBER,
  payload: {
    remember,
  },
})

export const setCSRF = (csrf: string) => ({
  type: SET_CSRF,
  payload: {
    csrf,
  },
})

export const setPrams = (request: any) => ({
  type: SET_PARAMS,
  payload: {
    request,
  },
})
