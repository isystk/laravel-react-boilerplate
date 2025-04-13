import React, { createContext, useReducer, useContext, ReactNode } from "react";
import MainService from "@/services/main";

// --- 型定義 ---
type AppState = {
    bool?: boolean;
    root: MainService | null;
};

type Action =
    | { type: "TOGGLE_STATE" }
    | { type: "SET_STATE"; payload: MainService };

const initialState: AppState = {
    bool: false,
    root: null,
};

// --- Reducer ---
function appReducer(state: AppState, action: Action): AppState {
    switch (action.type) {
        case "TOGGLE_STATE":
            return { ...state, bool: !state.bool };
        case "SET_STATE":
            return { ...state, root: action.payload };
        default:
            return state;
    }
}

// --- Context定義 ---
const AppStateContext = createContext<AppState | undefined>(undefined);
const AppDispatchContext = createContext<React.Dispatch<Action> | undefined>(undefined);

// --- Providerコンポーネント ---
export const AppProvider = ({ children }: { children: ReactNode }) => {
    const [state, dispatch] = useReducer(appReducer, initialState);

    return (
        <AppStateContext.Provider value={state}>
            <AppDispatchContext.Provider value={dispatch}>
                {children}
            </AppDispatchContext.Provider>
        </AppStateContext.Provider>
    );
};

// --- カスタムフックでContextを使いやすく ---
export function useAppState() {
    const context = useContext(AppStateContext);
    if (!context) throw new Error("useAppState must be used within AppProvider");
    return context;
}

export function useAppDispatch() {
    const context = useContext(AppDispatchContext);
    if (!context) throw new Error("useAppDispatch must be used within AppProvider");
    return context;
}
