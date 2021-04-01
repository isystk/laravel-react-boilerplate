import { useDispatch, useSelector } from 'react-redux'
import { push } from 'connected-react-router'
import { URL } from '../../common/constants/url'
import { Auth } from '../../store/StoreTypes'

export const useHeader = () => {
  const dispatch = useDispatch()
  return {
    auths: { ...useSelector(auths) },
    push_top: () => dispatch(push(URL.TOP)),
    push_login: () => dispatch(push(URL.LOGIN)),
    push_register: () => dispatch(push(URL.REGISTER)),
    push_mycart: () => dispatch(push(URL.MYCART)),
    push_contact: () => dispatch(push(URL.CONTACT)),
  }
}

const auths = (state): Auth => state.auth
