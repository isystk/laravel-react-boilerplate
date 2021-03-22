import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../components/layout'
import CardTemplate from '../components/CardTemplate'
import ShopTop from '../components/Shops/ShopTop'
import { NotFound } from "../components/NotFound";

const routes = (session)=> {
    return(
        <>
          <Layout>
            <main className="main">
              <Switch>
                <Route exact path={URL.HOME} component={ShopTop} />
                <Route exact path="/login" render={() => <CardTemplate title="ログイン" content="LoginForm" />} />
                <Route exact path="/register" render={() => <CardTemplate title="新規登録" content="RegisterForm" />} />
                <Route exact path="/password/reset" render={() => <CardTemplate title="パスワードのリセット" content="EMailForm" />} />
                <Route path="/password/reset/:id" render={(props) => <CardTemplate title="パスワードのリセット" content="ResetForm" params={props.match.params} />} />
                <Route exact path="/email/verify" render={() => <CardTemplate title="メールアドレスを確認しました。" content="Verify" />} />

                {
                 (() => {
                    // ログインしてなければログイン画面へとばす
                    if (session.id===undefined) {
                      return <Redirect to="/login" />
                    }
                    // 新規会員登録後、メール確認が未完了の場合
                    if(session.email_verified_at===null)
                    {
                      return <Redirect to="/email/verify" />
                    }
                  })()
                }

                { /* ★ログインユーザー専用ここから */ }
                <Route path="/home" render={() => <CardTemplate title="ダッシュボード" content="Home" />} />
                { /* ★ログインユーザー専用ここまで */ }

                <Route component={NotFound} />
              </Switch>
            </main>
          </Layout>
        </>
    )
}

export default routes
