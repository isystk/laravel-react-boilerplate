import { Likes } from '../store/StoreTypes'
import { LikesAppAction, READ_LIKES } from '../actions/index'

const initialState: Likes = {
  data: [],
}

export function LikesReducer(state = initialState, action: LikesAppAction): Likes
{
  switch (action.type) {
    case READ_LIKES:
      return action.response.likes
    default:
      return state
  }

  return state
}

export default LikesReducer
