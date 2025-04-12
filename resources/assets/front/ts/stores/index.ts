import { combineReducers } from "redux";
import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import { Dispatch } from "react";
import MainService from "@/services/main";

type App = {
    bool?: boolean;
    root: MainService | null;
};

const AppSlice = createSlice({
    name: "app",
    initialState: {} as App,
    reducers: {
        toggleState(state?) {
            state.bool = !state.bool;
        },
        setState(state, action: PayloadAction<MainService>) {
            state.root = action.payload;
        },
    },
});

// Actions
const { toggleState, setState } = AppSlice.actions;

// 外部からはこの関数を呼んでもらう
export const forceRender =
    () => async (dispatch: Dispatch<PayloadAction<App>>) => {
        // @ts-ignore
        dispatch(toggleState());
    };
export const setAppRoot =
    (appRoot: MainService) =>
    async (dispatch: Dispatch<PayloadAction<MainService>>) => {
        // @ts-ignore
        dispatch(setState(appRoot));
    };

export default combineReducers({
    app: AppSlice.reducer,
});
