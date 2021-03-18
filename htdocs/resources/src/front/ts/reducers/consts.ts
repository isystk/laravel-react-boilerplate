// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import * as _ from 'lodash'

import { Consts } from '../store/StoreTypes'
import { ConstsAppAction, READ_CONSTS } from '../actions/index'

const initialState: Consts = {}

export function ConstsReducer(state = initialState, action: ConstsAppAction): Consts {
  switch (action.type) {
    case READ_CONSTS:
      return _.mapKeys(action.response, 'name')
    default:
      return state
  }

  return state
}

export default ConstsReducer
