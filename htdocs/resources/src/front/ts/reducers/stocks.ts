import { Stocks, Page } from '../store/StoreTypes'
import { ShopsAppAction, READ_STOCKS } from '../actions/index'

const initialState: Stocks = {
  data: [],
  page: {} as Page,
}

export function StocksReducer(state = initialState, action: ShopsAppAction): Stocks {
  switch (action.type) {
    case READ_STOCKS:
      return action.response.stocks
    default:
      return state
  }

  return state
}

export default StocksReducer
