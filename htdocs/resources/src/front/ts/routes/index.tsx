import React from 'react'
import { Route, Switch, Redirect } from 'react-router'
import CardTemplate from '../components/CardTemplate'

const routes = (session)=> {
  console.log("routes!!", session);
    return(
        <>
            <div className="py-3">
                {(()=>{
                    if(session.id===undefined){
                      console.log("session.id===undefined!!");
                        return (
                            <Switch>
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
                                    <Route exact path="/" render={() => <CardTemplate title="Welcome" content="Welcome" />} />
                                    <Route path="/home" render={() => <CardTemplate title="Dashboard" content="Home" />} />
                                    <Redirect to="/home" />
                                </Switch>
                            )
                        }
                    }
                })()}
            </div>
        </>
    )
}

export default routes
