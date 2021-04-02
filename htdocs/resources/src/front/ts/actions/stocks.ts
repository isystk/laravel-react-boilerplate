import { Action } from 'redux'
// import { createAsyncThunk } from '@reduxjs/toolkit'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Stock } from '../store/StoreTypes'

/**
 * APIで返却されるデータ型を定義
 */
export interface StocksAppAction extends Action {
  result: boolean
  stocks: {
    current_page: number
    data: Stock[]
  }
}

export const READ_STOCKS = 'READ_STOCKS'

export const readStocks = (search: string) => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(`${API_ENDPOINT.SHOPS}${search}`)
  dispatch({ type: READ_STOCKS, payload: response })
}

// export const readStocks = createAsyncThunk(READ_STOCKS, async (search: string) =>
// {
//   console.log('call action')
//   const response = await API.get(`${API_ENDPOINT.SHOPS}${search}`)
//   console.log('response', response)
//   return {
//     payload: response,
//   }
// })
