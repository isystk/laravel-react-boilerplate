import React from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Form } from 'react-bootstrap'
import TextInput from '../Elements/TextInput'
import CheckInput from '../Elements/CheckInput'
import LoginButton from '../Elements/LoginButton'
import CSRFToken from '../Elements/CSRFToken'
import { setEmail, setRemember } from '../../actions/auth'

interface IProps {
  email: string
  remember: boolean
  setEmail
  setRemember
}

class LoginForm extends React.Component<IProps> {
    constructor(props) {
        super(props)
    }
    render(){
      console.log("LoginForm!!");
        return (
            <Form method="POST" action="/login" id="login-form">
                <CSRFToken />
                <TextInput
                    identity="email"
                    controlType="email"
                    label="E-Mail Address"
                    defaultValue={this.props.email}
                    action={this.props.setEmail}
                    autoFocus={true}
                    />
                <TextInput
                    identity="password"
                    controlType="password"
                    autoComplete="current-password"
                    label="Password"
                    />
                <CheckInput
                    identity="remember"
                    label="Remember Me"
                    checked={this.props.remember}
                    action={this.props.setRemember}
                    />
                <LoginButton />
            </Form>
        )
    }
}

const mapStateToProps = state => ({
    email: state.email,
    remember: state.remember,
})
const mapDispatchToProps = dispatch => ({
    setEmail: (email) => dispatch(setEmail(email)),
    setRemember: (remember) => dispatch(setRemember(remember)),
})
export default connect(mapStateToProps, mapDispatchToProps)(LoginForm)
