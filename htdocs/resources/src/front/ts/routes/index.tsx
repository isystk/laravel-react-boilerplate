import React from 'react'
import { Route, Switch } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../containers/layout'
import CardTemplate from '../containers/CardTemplate'
import ShopTop from '../components/Shops/ShopTop'
import MyCart from '../containers/Shops/MyCart'
import ShopComplete from '../containers/Shops/ShopComplete'
import ContactCreate from '../containers/Contacts/ContactCreate'
import ContactComplete from '../containers/Contacts/ContactComplete'
import AuthCheck from '../containers/Auths/AuthCheck'
import { NotFound } from '../components/NotFound'

const routes = (session: string) => {
  return (
    <>
      <Layout>
        <main className="main">
          <Switch>
            <Route exact path={URL.TOP} component={ShopTop} />
            <Route exact path={URL.LOGIN} render={() => <CardTemplate title="ログイン" content="LoginForm" />} />
            <Route exact path={URL.REGISTER} render={() => <CardTemplate title="新規登録" content="RegisterForm" />} />
            <Route
              exact
              path={URL.PASSWORD_RESET}
              render={() => <CardTemplate title="パスワードのリセット" content="EMailForm" />}
            />
            <Route
              path={`${URL.PASSWORD_RESET}/:id`}
              render={(props: { match: { params: any } }) => (
                <CardTemplate title="パスワードのリセット" content="ResetForm" params={props.match.params} />
              )}
            />
            <Route
              exact
              path={URL.EMAIL_VERIFY}
              render={() => <CardTemplate title="メールアドレスを確認しました。" content="Verify" />}
            />
            <Route exact path={URL.CONTACT} component={ContactCreate} />
            <Route exact path={URL.CONTACT_COMPLETE} component={ContactComplete} />

            {/* ★ログインユーザー専用ここから */}
            <AuthCheck session={session}>
              <Route exact path={URL.HOME} render={() => <CardTemplate title="ダッシュボード" content="Home" />} />
              <Route exact path={URL.MYCART} component={MyCart} />
              <Route exact path={URL.SHOP_COMPLETE} component={ShopComplete} />
            </AuthCheck>
            {/* ★ログインユーザー専用ここまで */}

            <Route component={NotFound} />
          </Switch>
        </main>
      </Layout>
    </>
  )
}

export default routes
