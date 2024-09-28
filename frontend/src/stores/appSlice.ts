import { configureStore, createSlice, PayloadAction } from '@reduxjs/toolkit'
import { Dispatch } from 'react'
import MainService from '@/services/main'

export type RootState = {
  app: App
}

export type App = {
  bool?: boolean
  root?: MainService
}

const AppSlice = createSlice({
  name: 'app',
  initialState: {},
  reducers: {
    toggleState(state?) {
      state.bool = !state.bool
    },
    setState(state, action: PayloadAction<MainService>) {
      state.root = action.payload
    },
  },
})

// Actions
const { toggleState, setState } = AppSlice.actions

// 外部からはこの関数を呼んでもらう
export const forceRender =
  () => async (dispatch: Dispatch<PayloadAction<App>>) => {
    // @ts-ignore
    dispatch(toggleState())
  }
export const setAppRoot =
  (appRoot: MainService) =>
  async (dispatch: Dispatch<PayloadAction<MainService>>) => {
    // @ts-inore
    dispatch(setState(appRoot))
  }

export const store = configureStore({
  reducer: {
    app: AppSlice.reducer,
  },
  //「A non-serializable value was detected」Error
  // See https://zenn.dev/luvmini511/articles/91a76a34909555
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware({
      serializableCheck: false,
    }),
})
