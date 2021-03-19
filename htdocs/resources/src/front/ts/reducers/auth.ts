import { Auth } from '../store/StoreTypes'
import { AuthAppAction, AUTH_CHECK, AUTH_LOGIN, AUTH_LOGOUT } from '../actions/index'

const initialState: Auth = {
  isLogin: false,
}

export function AuthReducer(state = initialState, action: AuthAppAction): Auth
{
  switch (action.type) {
    case AUTH_CHECK:
    case AUTH_LOGIN: {

      const { response } = action
      if (!response) {
        return {
          isLogin: false,
        }
      }
      return {
        isLogin: true,
        familyName: response[0].familyName,
      }
    }
    case AUTH_LOGOUT:
      return { isLogin: false }
    default:
      return state
  }

  return state
}

export default AuthReducer
