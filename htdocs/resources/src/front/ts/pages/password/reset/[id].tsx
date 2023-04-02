import React, { useState, FC } from "react";
import { Form } from "reactstrap";
import TextInput from "@/components/elements/TextInput";
import SubmitButton from "@/components/elements/SubmitButton";
import CSRFToken from "@/components/elements/CSRFToken";
import RequestToken from "@/components/elements/RequestToken";
import SessionAlert from "@/components/elements/SessionAlert";
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
        <Layout appRoot={appRoot} title="パスワード変更">
            <main className="main">
                <Box title="パスワード変更" small={true}>
                    <SessionAlert target="status" />
                    <Form
                        method="POST"
                        action="/reset-password"
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
