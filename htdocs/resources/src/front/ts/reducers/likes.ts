import { Likes } from '../store/StoreTypes'
import
{
  LikesAppAction, READ_LIKES,
  ADD_LIKE,
  REMOVE_LIKE
} from '../actions/index'

const initialState: Likes = {
  data: [],
}

export function LikesReducer(state = initialState, action: LikesAppAction): Likes
{
  switch (action.type) {
    case READ_LIKES:
      return action.response.likes
    case ADD_LIKE:
      state.data.push(action.id + '')
      return { ...state, data: state.data }
    case REMOVE_LIKE:
      return { ...state, data: state.data.filter(n => n !== action.id + '') }
    default:
      return state
  }

  return state
}

export default LikesReducer
