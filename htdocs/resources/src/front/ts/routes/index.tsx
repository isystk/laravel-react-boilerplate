import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../components/layout'
import CardTemplate from '../components/CardTemplate'
import ShopTop from '../components/Shops/ShopTop'
import AuthCheck from '../components/Auths/AuthCheck'
import { NotFound } from "../components/NotFound";

const routes = (session)=> {
    return(
        <>
          <Layout>
            <main className="main">
              <Switch>
                <Route exact path={URL.TOP} component={ShopTop} />
                <Route exact path={URL.LOGIN} render={() => <CardTemplate title="ログイン" content="LoginForm" />} />
                <Route exact path={URL.REGISTER} render={() => <CardTemplate title="新規登録" content="RegisterForm" />} />
                <Route exact path={URL.PASSWORD_RESET} render={() => <CardTemplate title="パスワードのリセット" content="EMailForm" />} />
                <Route path={`${URL.PASSWORD_RESET}/:id`} render={(props) => <CardTemplate title="パスワードのリセット" content="ResetForm" params={props.match.params} />} />
                <Route exact path={URL.EMAIL_VERIFY} render={() => <CardTemplate title="メールアドレスを確認しました。" content="Verify" />} />

                { /* ★ログインユーザー専用ここから */ }
                <AuthCheck session={session} >
                  <Route path={URL.HOME} render={() => <CardTemplate title="ダッシュボード" content="Home" />} />
                </AuthCheck>
                { /* ★ログインユーザー専用ここまで */ }

                <Route component={NotFound} />
              </Switch>
            </main>
          </Layout>
        </>
    )
}

export default routes
