import { Action } from 'redux'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Const } from '../store/StoreTypes'

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface ConstsAppAction extends Action {
  response: Const
}

export const READ_CONSTS = 'READ_CONSTS'

export const readConst = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(API_ENDPOINT.COMMON_CONST)
  dispatch({ type: READ_CONSTS, response })
}
