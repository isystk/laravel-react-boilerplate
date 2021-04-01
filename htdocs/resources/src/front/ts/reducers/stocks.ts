import { Stocks, Page } from '../store/StoreTypes'

const initialState: Stocks = {
  data: [],
  page: {} as Page,
}

export const StocksReducer = (state = initialState, action: any): Stocks => {
  switch (action.type) {
    case 'READ_STOCKS':
      return action.response.stocks
    default:
      return state
  }

  return state
}

export default StocksReducer
