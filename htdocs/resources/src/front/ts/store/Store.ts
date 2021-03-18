// Storeとは、アプリケーションの状態(state)を保持します。
// getState()を介してstateへのアクセスを許可します。
// 状態をdispatch（アクション）によって更新できるようにする。
// subscribe（listener）を介してlistenerを登録します。
// subscribe（listener）によって返された関数を介して、listenerの登録解除を処理します。

import { combineReducers, createStore, ReducersMapObject } from "redux";

import { AuthReducer } from "../reducers/auth";
import { ConstsReducer } from "../reducers/consts";
import { EntryReducer } from "../reducers/entry";
import { MemberPostsReducer } from "../reducers/member_posts";
import { PartsReducer } from "../reducers/parts";
import { PostsReducer } from "../reducers/posts";
import { RemindReducer } from "../reducers/remind";

const reducers: ReducersMapObject = {
};

declare let window: any;

const rootReducer = combineReducers({
  AuthReducer,
  ConstsReducer,
  EntryReducer,
  MemberPostsReducer,
  PartsReducer,
  PostsReducer,
  RemindReducer,
});

export default createStore(
  rootReducer,
  window.devToolsExtension ? window.devToolsExtension() : undefined
);
