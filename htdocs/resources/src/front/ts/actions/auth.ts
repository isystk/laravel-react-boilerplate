import { Action } from 'redux'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Auth } from '../store/StoreTypes'

export interface AuthAppAction extends Action
{
  response: Auth[]
}

export const AUTH_CHECK = 'AUTH_CHECK'
export const AUTH_LOGIN = 'AUTH_LOGIN'
export const AUTH_LOGOUT = 'AUTH_LOGOUT'

export const authCheck = () => async (dispatch: Dispatch): Promise<void> =>
{
  const response = await API.post(API_ENDPOINT.LOGIN_CHECK)
  dispatch({ type: AUTH_CHECK, response })
}

export const authLogin = (values: any) => async (dispatch: Dispatch): Promise<void> =>
{
  const response = await API.post(API_ENDPOINT.LOGIN, values)
  dispatch({ type: AUTH_LOGIN, response })
}

export const authLogout = () => async (dispatch: Dispatch): Promise<void> =>
{
  const response = await API.post(API_ENDPOINT.LOGOUT)
  dispatch({ type: AUTH_LOGOUT, response })
}
