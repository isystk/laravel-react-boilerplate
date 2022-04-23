import React, { useState, VFC } from "react";
import { Form } from "react-bootstrap";
import TextInput from "@/components/Elements/TextInput";
import SubmitButton from "@/components/Elements/SubmitButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

const RegisterForm: VFC = () => {
    const [name, setName] = useState<string>("");
    const [email, setEmail] = useState<string>("");
    return (
        <Layout>
            <main className="main">
                <Box title="会員登録">
                    <Form method="POST" action="/register" id="login-form">
                        <CSRFToken />
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
                            identity="password-confirm"
                            controlType="password"
                            name="password_confirmation"
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
