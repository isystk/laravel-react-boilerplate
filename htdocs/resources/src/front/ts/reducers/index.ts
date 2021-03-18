import { combineReducers } from "redux";
import { reducer as form } from "redux-form";
import consts from "./consts";
import stocks from "./stocks";
import auth from "./auth";

export default combineReducers({ consts, stocks, auth, form });
