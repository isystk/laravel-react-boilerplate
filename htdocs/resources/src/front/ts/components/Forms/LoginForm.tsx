import React from 'react'
import { connect } from 'react-redux'
import { Form } from 'react-bootstrap'
import TextInput from '../Elements/TextInput'
import LoginButton from '../Elements/LoginButton'
import CSRFToken from '../Elements/CSRFToken'
import { setEmail, setRemember } from '../../actions/auth'

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
      <React.Fragment>
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
          <div className="form-section">
            <label className="checkbox-wrap">
              <Form.Check
                id="remember"
                type="checkbox"
                className="form-check-input"
                name="remember"
                checked={this.props.remember}
                onChange={check => this.props.setRemember(check.target.checked)}
              />
              <span>Remember Me</span>
            </label>
          </div>
          <p className="fz-s">
            email: test1@test.com
            <br />
            password: password
          </p>
          <LoginButton />
        </Form>
      </React.Fragment>
    )
  }
}

const mapStateToProps = state => ({
  email: state.email,
  remember: state.remember,
})
const mapDispatchToProps = dispatch => ({
  setEmail: email => dispatch(setEmail(email)),
  setRemember: remember => dispatch(setRemember(remember)),
})
export default connect(mapStateToProps, mapDispatchToProps)(LoginForm)
