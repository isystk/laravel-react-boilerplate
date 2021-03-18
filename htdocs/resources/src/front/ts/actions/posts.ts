import { Action } from "redux";
import axios from "axios";
import { Dispatch } from "redux";
import { API_ENDPOINT } from "../common/constants/api";
import { API } from "../utilities";
import { Posts } from "../store/StoreTypes";

//  アクションは『何か』が起こった時、Storeに『どんなデータ』を利用するかということを定義します。
//  ActionCreatorであるstore.dispatch()を使ってStoreに送信しますが、storeについてはこの記事の最後にまとめています。(※ こちら)
//  ただし、アプリケーションの状態がどのように変化するかはここでは指定しません。(→Reducerがやること)
//  あくまでどんな挙動があるかだけを定義します。
export interface PostsAppAction extends Action {
  response: Posts;
}

export const READ_POSTS = "READ_POSTS";
export const READ_POST = "READ_POST";

export const readPosts = () => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(API_ENDPOINT.POSTS);
  dispatch({ type: READ_POSTS, response });
};

export const getPost = (id: number) => async (dispatch: Dispatch): Promise<void> => {
  const response = await API.get(`${API_ENDPOINT.POSTS}/${id}`);
  dispatch({ type: READ_POST, response });
};
