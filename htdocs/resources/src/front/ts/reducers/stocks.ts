import { createReducer, PayloadAction } from '@reduxjs/toolkit'
import { StocksAppAction, READ_STOCKS } from '../actions'
import { Stock } from '../store/StoreTypes'

const initialState = {
  current_page: 1,
  total: 0,
  data: [] as Stock[],
}

const StocksReducer = createReducer(initialState, {
  [READ_STOCKS]: (state, action: PayloadAction<StocksAppAction>) => {
    console.log(action)
    return {
      ...state,
      current_page: action.payload.stocks.current_page,
      total: action.payload.stocks.total,
      data: action.payload.stocks.data,
    }
  },
})

export default StocksReducer
