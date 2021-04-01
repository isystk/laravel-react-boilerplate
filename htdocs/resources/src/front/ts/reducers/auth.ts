import { Auth } from '../store/StoreTypes'
import { AuthAppAction, SET_SESSION, SET_NAME, SET_EMAIL, SET_REMEMBER, SET_CSRF, SET_PARAMS } from '../actions/index'

const initialState: Auth = {
  auth: false,
  id: null,
  name: null,
  email: '',
  remember: '',
  csrf: '',
  request: '',
}

export const AuthReducer = (state = initialState, action: AuthAppAction): Auth => {
  switch (action.type) {
    case SET_SESSION: {
      const session =
        action.payload.id === undefined ? { auth: false, name: null } : { auth: true, name: action.payload.name }
      return session
    }
    case SET_NAME:
      return { ...state, name: action.payload.name }
    case SET_EMAIL:
      return { ...state, email: action.payload.email }
    case SET_REMEMBER:
      return { ...state, remember: action.payload.remember }
    case SET_CSRF:
      return { ...state, csrf: action.payload.csrf }
    case SET_PARAMS:
      return { ...state, request: action.payload.request }
    default:
      return state
  }

  return state
}

export default AuthReducer
