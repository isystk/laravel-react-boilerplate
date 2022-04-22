import React from "react";
import { Form } from "react-bootstrap";
import TextInput from "../Elements/TextInput";
import SubmitButton from "../Elements/SubmitButton";
import CSRFToken from "../../components/Elements/CSRFToken";
import RequestToken from "../Elements/RequestToken";
import SessionAlert from "../Elements/SessionAlert";
import { connect } from "react-redux";
import { setEmail } from "@/actions";

type Props = {
    email;
    setEmail;
};

class ResetForm extends React.Component<Props> {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <>
                <SessionAlert target="status" />
                <Form method="POST" action="/password/reset" id="login-form">
                    <CSRFToken />
                    <RequestToken />
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
                    <SubmitButton label="Reset Password" />
                </Form>
            </>
        );
    }
}

const mapStateToProps = state => ({
    email: state.email
});

const mapDispatchToProps = dispatch => ({
    setEmail: email => dispatch(setEmail(email))
});

export default connect(mapStateToProps, mapDispatchToProps)(ResetForm);
