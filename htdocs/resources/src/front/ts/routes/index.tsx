import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../components/layout'
import CardTemplate from '../components/CardTemplate'
import ShopTop from '../components/Shops/ShopTop'
import { NotFound } from "../components/NotFound";

const routes = (session)=> {
    console.log("session!!", session);
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
                <Route path="/home" render={() => <CardTemplate title="Dashboard" content="Home" />} />
                <Route component={NotFound} />
              </Switch>
            </main>
          </Layout>
        </>
    )
}

export default routes
