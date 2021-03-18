// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import { Likes } from '../store/StoreTypes'
import { LikesAppAction, READ_LIKES } from '../actions/index'

const initialState: Likes = {
  data: [],
}

export function LikesReducer(state = initialState, action: LikesAppAction): Likes {
  switch (action.type) {
    case READ_LIKES:
      return action.response.likes
    default:
      return state
  }

  return state
}

export default LikesReducer
