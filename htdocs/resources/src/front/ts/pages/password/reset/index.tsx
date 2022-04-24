import React, { FC } from "react";
import { Form } from "react-bootstrap";
import TextInput from "@/components/Elements/TextInput";
import SubmitButton from "@/components/Elements/SubmitButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import SessionAlert from "@/components/Elements/SessionAlert";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const ResetForm: FC<Props> = ({ appRoot }) => {
    return (
        <Layout appRoot={appRoot}>
            <main className="main">
                <Box title="パスワードのリセット">
                    <SessionAlert target="status" />
                    <Form
                        method="POST"
                        action="/password/email"
                        id="login-form"
                    >
                        <CSRFToken appRoot={appRoot} />
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="メールアドレス"
                            autoFocus={true}
                        />
                        <SubmitButton label="メールを送信する" />
                    </Form>
                </Box>
            </main>
        </Layout>
    );
};

export default ResetForm;
