import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import { URL } from '../common/constants/url'

import Layout from '../components/layout'
import CardTemplate from '../components/CardTemplate'
import ShopIndex from '../components/shop/shop_index'


const routes = (session)=> {
  console.log("routes!!", session);
    return(
        <>
          <Layout>
            <main className="main">
                {(()=>{
                    if(session.id===undefined){
                      console.log("session.id===undefined!!");
                        return (
                            <Switch>
                                <Route exact path={URL.HOME} component={ShopIndex} />
                                <Route exact path="/login" render={() => <CardTemplate title="Login" content="LoginForm" />} />
                                <Route exact path="/register" render={() => <CardTemplate title="Register" content="RegisterForm" />} />
                                <Route exact path="/password/reset" render={() => <CardTemplate title="Reset Password" content="EMailForm" />} />
                                <Route path="/password/reset/:id" render={(props) => <CardTemplate title="Reset Password" content="ResetForm" params={props.match.params} />} />
                                <Redirect to="/login" />
                            </Switch>
                        )
                    }
                    else
                    {
                        if(session.email_verified_at===null)
                        {
                          console.log("session.email_verified_at===null");
                            return (
                                <Switch>
                                    <Route exact path="/email/verify" render={() => <CardTemplate title="Verify Your Email Address" content="Verify" />} />
                                    <Redirect to="/email/verify" />
                                </Switch>
                            )
                        }
                        else
                        {
                          console.log("session.email_verified_at!==null");
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
