import { Action } from 'redux'
import { Dispatch } from 'redux'
import { Auth } from '../store/StoreTypes'

export interface AuthAppAction extends Action
{
  payload: Auth
}

export const SET_SESSION = 'SET_SESSION'
export const SET_NAME = 'SET_NAME'
export const SET_EMAIL = 'SET_EMAIL'
export const SET_REMEMBER = 'SET_REMEMBER'
export const SET_CSRF = 'SET_CSRF'
export const SET_PARAMS = 'SET_PARAMS'

export const setSession = (session) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    id: session.id,
    name: session.name
  }
  dispatch({ type: SET_SESSION, payload })
}

export const setName = (name) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    name
  }
  dispatch({ type: SET_NAME, payload })
}

export const setEmail = (email) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    email
  }
  dispatch({ type: SET_EMAIL, payload })
}


export const setRemember = (remember) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    remember
  }
  dispatch({ type: SET_REMEMBER, payload })
}


export const setCSRF = (csrf) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    csrf
  }
  dispatch({ type: SET_CSRF, payload })
}


export const setPrams = (request) => async (dispatch: Dispatch): Promise<void> =>
{
  const payload = {
    request
  }
  dispatch({ type: SET_PARAMS, payload })
}
