import React, { useState, FC } from "react";
import { Form } from "react-bootstrap";
import TextInput from "@/components/Elements/TextInput";
import SubmitButton from "@/components/Elements/SubmitButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import RequestToken from "@/components/Elements/RequestToken";
import SessionAlert from "@/components/Elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const ResetForm: FC<Props> = ({ appRoot }) => {
    const [email, setEmail] = useState<string>("");

    const handleSetEmail = (value: string) => {
        setEmail(value);
    };

    return (
        <Layout appRoot={appRoot}>
            <main className="main">
                <Box title="パスワードのリセット">
                    <SessionAlert target="status" />
                    <Form
                        method="POST"
                        action="/password/reset"
                        id="login-form"
                    >
                        <CSRFToken appRoot={appRoot} />
                        <RequestToken />
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="メールアドレス"
                            defaultValue={email}
                            action={handleSetEmail}
                            autoFocus={true}
                        />
                        <TextInput
                            identity="password"
                            controlType="password"
                            autoComplete="new-password"
                            label="新しいパスワード"
                        />
                        <TextInput
                            identity="password_confirmation"
                            controlType="password"
                            autoComplete="new-password"
                            label="新しいパスワード(確認)"
                        />
                        <SubmitButton label="パスワードを変更する" />
                    </Form>
                </Box>
            </main>
        </Layout>
    );
};

export default ResetForm;
