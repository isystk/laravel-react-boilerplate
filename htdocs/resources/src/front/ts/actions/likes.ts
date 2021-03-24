import { Action } from "redux";
import { Dispatch } from "redux";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Likes } from "../store/StoreTypes";

export interface LikesAppAction extends Action {
  response: {
    result: boolean;
    likes: Likes;
  };
  id: number;
}

export const READ_LIKES = "READ_LIKES";
export const ADD_LIKE = "ADD_LIKE";
export const REMOVE_LIKE = "REMOVE_LIKE";

export const readLikes = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(API_ENDPOINT.LIKES);
  dispatch({ type: READ_LIKES, response });
};

export const addLike = (id: number) => async (
  dispatch: Dispatch
): Promise<void> => {
  const response = await API.post(API_ENDPOINT.LIKES_STORE, { id: id });
  if (response.result) {
    window.alert("お気に入りに追加しました");
    dispatch({ type: ADD_LIKE, id });
  }
};

export const removeLike = (id: number) => async (
  dispatch: Dispatch
): Promise<void> => {
  const response = await API.post(API_ENDPOINT.LIKES_DESTROY + "/" + id);
  if (response.result) {
    dispatch({ type: REMOVE_LIKE, id });
  }
};
