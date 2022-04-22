import React from "react";
import { Form } from "react-bootstrap";
import TextInput from "../Elements/TextInput";
import SubmitButton from "../Elements/SubmitButton";
import CSRFToken from "../../components/Elements/CSRFToken";
import { connect } from "react-redux";
import { setEmail, setName } from "@/actions";

type Props = {
    name: string;
    email: string;
    setName;
    setEmail;
};

class RegisterForm extends React.Component<Props> {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <Form method="POST" action="/register" id="login-form">
                <CSRFToken />
                <TextInput
                    identity="name"
                    controlType="text"
                    label="お名前"
                    defaultValue={this.props.name}
                    action={this.props.setName}
                    autoFocus={true}
                />
                <TextInput
                    identity="email"
                    controlType="email"
                    label="メールアドレス"
                    defaultValue={this.props.email}
                    action={this.props.setEmail}
                    autoFocus={false}
                />
                <TextInput
                    identity="password"
                    controlType="password"
                    autoComplete="new-password"
                    label="パスワード"
                />
                <TextInput
                    identity="password-confirm"
                    controlType="password"
                    name="password_confirmation"
                    autoComplete="new-password"
                    label="パスワード（確認）"
                />
                <SubmitButton label="新規登録" />
            </Form>
        );
    }
}

const mapStateToProps = state => ({
    name: state.name,
    email: state.email
});

const mapDispatchToProps = dispatch => ({
    setName: name => dispatch(setName(name)),
    setEmail: email => dispatch(setEmail(email))
});

export default connect(mapStateToProps, mapDispatchToProps)(RegisterForm);
