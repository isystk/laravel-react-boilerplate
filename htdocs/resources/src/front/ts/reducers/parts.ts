import { Parts } from '../store/StoreTypes'
import { PartsAppAction, SHOW_OVERLAY, HIDE_OVERLAY } from '../actions/index'
import produce from 'immer'

const initialState: Parts = {
  isShowOverlay: false,
}

export const PartsReducer = (state = initialState, action: PartsAppAction): Parts =>
  produce(state, draft =>
  {
    switch (action.type) {
      case SHOW_OVERLAY:
        draft['isShowOverlay'] = true
        return
      case HIDE_OVERLAY:
        draft['isShowOverlay'] = false
        return
      default:
        return state
    }

    return state
  })

export default PartsReducer
