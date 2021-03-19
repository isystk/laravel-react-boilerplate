import { Action } from 'redux'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Stocks } from '../store/StoreTypes'

export interface ShopsAppAction extends Action
{
  response: {
    result: boolean
    stocks: Stocks
  }
}

export const READ_STOCKS = 'READ_STOCKS'

export const readShops = (url = API_ENDPOINT.SHOPS) => async (dispatch: Dispatch): Promise<void> =>
{
  const response = await API.get(url)
  dispatch({ type: READ_STOCKS, response })
}
