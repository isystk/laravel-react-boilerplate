import React from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Form } from 'react-bootstrap'
import TextInput from '../Elements/TextInput'
import SubmitButton from '../Elements/SubmitButton'
import CSRFToken from '../Elements/CSRFToken'
import { setName, setEmail } from '../../actions/auth'

interface IProps {
  name:  string
  email:  string
  setName
  setEmail
}

class LoginForm extends React.Component<IProps> {
    constructor(props) {
        super(props)
    }
    render(){
        return (
            <Form method="POST" action="/register" id="login-form">
                <CSRFToken />
                <TextInput
                    identity="name"
                    controlType="text"
                    label="Name"
                    defaultValue={this.props.name}
                    action={this.props.setName}
                    autoFocus={true}
                    />
                <TextInput
                    identity="email"
                    controlType="email"
                    label="E-Mail Address"
                    defaultValue={this.props.email}
                    action={this.props.setEmail}
                    autoFocus={false}
                    />
                <TextInput
                    identity="password"
                    controlType="password"
                    autoComplete="new-password"
                    label="Password"
                    />
                <TextInput
                    identity="password-confirm"
                    controlType="password"
                    name="password_confirmation"
                    autoComplete="new-password"
                    label="Confirm Password"
                    />
                <SubmitButton label="Register" />
            </Form>
        )
    }
}

const mapStateToProps = state => ({
    name: state.name,
    email: state.email,
})
const mapDispatchToProps = dispatch => ({
    setName: (name) => dispatch(setName(name)),
    setEmail: (email) => dispatch(setEmail(email)),
})
export default connect(mapStateToProps, mapDispatchToProps)(LoginForm)
