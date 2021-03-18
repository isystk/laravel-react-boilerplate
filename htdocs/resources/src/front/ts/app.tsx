import * as React from "react";
import * as ReactDom from "react-dom";
import { createStore, applyMiddleware } from "redux";
import { Provider } from "react-redux";
import thunk from "redux-thunk";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import { composeWithDevTools } from "redux-devtools-extension";
import MuiThemeProvider from "material-ui/styles/MuiThemeProvider";
import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { fas } from "@fortawesome/free-solid-svg-icons";
library.add(fab, far, fas);
import { URL } from "./common/constants/url";

import reducers from "./reducers";
import Layout from "./components/layout";
import ShopIndex from "./components/shop/shop_index";
import AuthCheck from "./components/auth/auth_check";
import { NotFound } from "./components/NotFound";

import 'bootstrap';
import 'heic2any';


// 開発環境の場合は、redux-devtools-extension を利用できるようにする
const enhancer =
  process.env.NODE_ENV === "development"
    ? composeWithDevTools(applyMiddleware(thunk))
    : applyMiddleware(thunk);
const store = createStore(reducers, enhancer);

const Main = () => (
  <main className="main">
    <Switch>
      <Route exact path={URL.HOME} component={ShopIndex} />

      { /* ★ログインユーザー専用ここから */ }
      <AuthCheck>
      </AuthCheck>
      { /* ★ログインユーザー専用ここまで */ }

      <Route component={NotFound} />
    </Switch>
  </main>
)

ReactDom.render(
  <MuiThemeProvider>
    <Provider store={store}>
      <Router>
        <Layout>
          <Main />
        </Layout>
      </Router>
    </Provider>
  </MuiThemeProvider>,
  document.getElementById("app")
);
