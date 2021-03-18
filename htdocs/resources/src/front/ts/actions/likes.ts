import { Action } from 'redux'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Likes } from '../store/StoreTypes'

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface LikesAppAction extends Action {
  response: {
    result: boolean
    likes: Likes
  }
}

export const READ_LIKES = 'READ_LIKES'

export const readLikes = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(API_ENDPOINT.SHOPS)
  dispatch({ type: READ_LIKES, response })
}
