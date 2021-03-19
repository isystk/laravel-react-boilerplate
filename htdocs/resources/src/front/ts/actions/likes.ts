import { Action } from 'redux'
import { Dispatch } from 'redux'
import { API_ENDPOINT } from '../common/constants/api'
import { API } from '../utilities'
import { Likes } from '../store/StoreTypes'

export interface LikesAppAction extends Action
{
  response: {
    result: boolean
    likes: Likes
  }
}

export const READ_LIKES = 'READ_LIKES'

export const readLikes = () => async (dispatch: Dispatch): Promise<void> =>
{
  const response = await API.get(API_ENDPOINT.LIKES)
  dispatch({ type: READ_LIKES, response })
}
