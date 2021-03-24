import { Carts } from '../store/StoreTypes'
import { CartsAppAction, READ_CARTS } from '../actions/index'

const initialState: Carts = {
  data: [],
  message: '',
  count: 0,
  sum: 0,
}

export function CartsReducer(state = initialState, action: CartsAppAction): Carts {
  switch (action.type) {
    case READ_CARTS:
      return action.response.carts
    default:
      return state
  }

  return state
}

export default CartsReducer
