import React, { FC } from "react";
import { Form } from "react-bootstrap";
import TextInput from "@/components/Elements/TextInput";
import SubmitButton from "@/components/Elements/SubmitButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import RequestToken from "@/components/Elements/RequestToken";
import SessionAlert from "@/components/Elements/SessionAlert";
import { setEmail } from "@/actions";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import { useDispatch } from "react-redux";

type Props = {
    email;
};

const ResetForm: FC<Props> = ({ email }) => {
    const dispatch = useDispatch();

    const handleSetEmail = () => {
        dispatch(setEmail);
    };

    return (
        <Layout>
            <main className="main">
                <Box title="パスワードのリセット">
                    <SessionAlert target="status" />
                    <Form
                        method="POST"
                        action="/password/reset"
                        id="login-form"
                    >
                        <CSRFToken />
                        <RequestToken />
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="E-Mail Address"
                            defaultValue={email}
                            action={handleSetEmail}
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
                </Box>
            </main>
        </Layout>
    );
};

export default ResetForm;
