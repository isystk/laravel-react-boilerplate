import React from "react";
import { Form } from "react-bootstrap";
import TextInput from "@/components/Elements/TextInput";
import SubmitButton from "@/components/Elements/SubmitButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import SessionAlert from "@/components/Elements/SessionAlert";
import { connect } from "react-redux";
import { setEmail } from "@/services/actions";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

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
            <Layout>
                <main className="main">
                    <Box title="パスワードのリセット">
                        <SessionAlert target="status" />
                        <Form
                            method="POST"
                            action="/password/email"
                            id="login-form"
                        >
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
                    </Box>
                </main>
            </Layout>
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
