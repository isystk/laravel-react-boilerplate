import React from "react";
import { Form } from "react-bootstrap";
import TextInput from "../Elements/TextInput";
import SubmitButton from "../Elements/SubmitButton";
import CSRFToken from "../../components/Elements/CSRFToken";
import SessionAlert from "../Elements/SessionAlert";
import { connect } from "react-redux";
import { setEmail } from "@/actions";

type Props = {
    email: string;
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
                <Form method="POST" action="/password/email" id="login-form">
                    <CSRFToken />
                    <TextInput
                        identity="email"
                        controlType="email"
                        label="E-Mail Address"
                        defaultValue={this.props.email}
                        action={this.props.setEmail}
                        autoFocus={true}
                    />
                    <SubmitButton label="Send Password Reset Link" />
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
