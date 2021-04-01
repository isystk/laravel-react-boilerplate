import { Parts } from '../store/StoreTypes'
import { PartsAppAction, SHOW_OVERLAY, HIDE_OVERLAY } from '../actions/index'

const initialState: Parts = {
  isShowOverlay: false,
}

export const PartsReducer = (state = initialState, action: PartsAppAction): Parts => {
  switch (action.type) {
    case SHOW_OVERLAY:
      return { ...state, isShowOverlay: true }
    case HIDE_OVERLAY:
      return { ...state, isShowOverlay: false }
    default:
      return state
  }

  return state
}

export default PartsReducer
