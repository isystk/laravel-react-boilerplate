import React, { useState, FC } from "react";
import { Form } from "reactstrap";
import TextInput from "@/components/elements/TextInput";
import SubmitButton from "@/components/elements/SubmitButton";
import CSRFToken from "@/components/elements/CSRFToken";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const RegisterForm: FC<Props> = ({ appRoot }) => {
    const [name, setName] = useState<string>("");
    const [email, setEmail] = useState<string>("");
    return (
        <Layout appRoot={appRoot} title="会員登録">
            <main className="main">
                <Box title="会員登録" small={true}>
                    <Form method="POST" action="/register" id="login-form">
                        <CSRFToken appRoot={appRoot} />
                        <TextInput
                            identity="name"
                            controlType="text"
                            label="お名前"
                            defaultValue={name}
                            action={setName}
                            autoFocus={true}
                        />
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="メールアドレス"
                            defaultValue={email}
                            action={setEmail}
                            autoFocus={false}
                        />
                        <TextInput
                            identity="password"
                            controlType="password"
                            autoComplete="new-password"
                            label="パスワード"
                        />
                        <TextInput
                            identity="password_confirmation"
                            controlType="password"
                            autoComplete="new-password"
                            label="パスワード（確認）"
                        />
                        <SubmitButton label="新規登録" />
                    </Form>
                </Box>
            </main>
        </Layout>
    );
};

export default RegisterForm;
