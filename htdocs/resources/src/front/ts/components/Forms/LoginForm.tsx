import React from 'react'

import { Form, Input, Col, Row } from 'reactstrap'
import TextInput from '../Elements/TextInput'
import LoginButton from '../Elements/LoginButton'
import CSRFToken from '../../containers/Elements/CSRFToken'

type Props = {
  email: string
  remember: boolean
  setEmail
  setRemember
}

class LoginForm extends React.Component<Props> {
  constructor(props) {
    super(props)
  }
  render() {
    return (
      <>
        <div className="text-center mb-3  ">
          <form method="GET" action="/auth/google">
            <button type="submit" className="btn btn-danger">
              Googleアカウントでログイン
            </button>
          </form>
        </div>
        <Form method="POST" action="/login" id="login-form">
          <CSRFToken />
          <TextInput
            identity="email"
            controlType="email"
            label="メールアドレス"
            defaultValue={this.props.email}
            action={this.props.setEmail}
            autoFocus={true}
          />
          <TextInput identity="password" controlType="password" autoComplete="current-password" label="パスワード" />
          <Row className="form-group mt-3">
            <Col className="text-center">
              <div className="form-section">
                <div className="checkbox-wrap">
                  <label>
                    <Input
                      type="checkbox"
                      id="remember"
                      name="remember"
                      className="form-check-input"
                      value="1"
                      onChange={check => this.props.setRemember(check.target.checked)}
                    />{' '}
                    <span>Remember Me</span>
                  </label>
                </div>
              </div>
              <p className="fz-s">
                email: test1@test.com
                <br />
                password: password
              </p>
            </Col>
          </Row>
          <LoginButton />
        </Form>
      </>
    )
  }
}

export default LoginForm
