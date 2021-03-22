import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../components/layout'
import CardTemplate from '../components/CardTemplate'
import ShopIndex from '../components/shop/shop_index'

const routes = (session)=> {
    return(
        <>
          <Layout>
            <main className="main">
                {(()=>{
                    if(session.id===undefined){
                      // 未ログインの場合
                        return (
                            <Switch>
                                <Route exact path={URL.HOME} component={ShopIndex} />
                                <Route exact path="/login" render={() => <CardTemplate title="ログイン" content="LoginForm" />} />
                                <Route exact path="/register" render={() => <CardTemplate title="新規登録" content="RegisterForm" />} />
                                <Route exact path="/password/reset" render={() => <CardTemplate title="パスワードのリセット" content="EMailForm" />} />
                                <Route path="/password/reset/:id" render={(props) => <CardTemplate title="パスワードのリセット" content="ResetForm" params={props.match.params} />} />
                                <Redirect to="/login" />
                            </Switch>
                        )
                    }
                    else
                    {
                      // ログイン済みの場合
                        if(session.email_verified_at===null)
                        {
                          // 新規会員登録後、メール確認が未完了の場合
                            return (
                                <Switch>
                                    <Route exact path="/email/verify" render={() => <CardTemplate title="Verify Your Email Address" content="Verify" />} />
                                    <Redirect to="/email/verify" />
                                </Switch>
                            )
                        }
                        else
                        {
                            return (
                                <Switch>
                                    <Route path="/home" render={() => <CardTemplate title="Dashboard" content="Home" />} />
                                    <Redirect to="/home" />
                                </Switch>
                            )
                        }
                    }
                })()}
            </main>
          </Layout>
        </>
    )
}

export default routes
