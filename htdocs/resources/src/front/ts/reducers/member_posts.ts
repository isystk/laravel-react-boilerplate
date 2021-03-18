// Reducerは、Stateの状態をデザインして、アクションをハンドリングします。
// また、Reducerは、前の状態とアクションを取り、次の状態を返す純粋な関数です。

import * as _ from "lodash";

import { Posts } from "../store/StoreTypes";
import {
  MemberPostsAppAction,
  CREATE_MEMBER_POST,
  READ_MEMBER_POSTS,
  READ_MEMBER_POST,
  UPDATE_MEMBER_POST,
  DELETE_MEMBER_POST,
} from "../actions/index";

const initialState: Posts = {
};

export function MemberPostsReducer(
  state = initialState,
  action: MemberPostsAppAction
): Posts {

  switch (action.type) {
    case CREATE_MEMBER_POST:
    case READ_MEMBER_POST:
    case UPDATE_MEMBER_POST:
      const data = action.response[0];
      return { ...state, [data.postId]: data };
    case READ_MEMBER_POSTS:
      return _.mapKeys(action.response, "postId");
    case DELETE_MEMBER_POST:
      delete state[action.id];
      return { ...state };
    default:
      return state;
  }

  return state;
}

export default MemberPostsReducer;
