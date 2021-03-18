// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import { Reducer } from "redux";
import * as object_assign from "object-assign";
import * as _ from "lodash";

import { Posts } from "../store/StoreTypes";
import {
  PostsAppAction,
  READ_POSTS,
  READ_POST,
} from "../actions/index";

const initialState: Posts = {
};

export function PostsReducer(
  state = initialState,
  action: PostsAppAction
): Posts {

  switch (action.type) {
    case READ_POST:
      const data = action.response[0];
      return { ...state, [data.postId]: data };
    case READ_POSTS:
      return _.mapKeys(action.response, "postId");
    default:
      return state;
  }

  return state;
}

export default PostsReducer;
