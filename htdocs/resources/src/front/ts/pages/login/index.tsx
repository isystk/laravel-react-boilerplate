import React, { FC, useState } from "react";

import { Form, Button, Input, Col, Row, FormGroup } from "reactstrap";
import TextInput from "@/components/elements/TextInput";
import LoginButton from "@/components/elements/LoginButton";
import CSRFToken from "@/components/elements/CSRFToken";
import ReCAPTCHA from "react-google-recaptcha";
import Box from "@/components/Box";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import { Url } from "@/constants/url";

type Props = {
    appRoot: MainService;
};

const LoginForm: FC<Props> = ({ appRoot }) => {
    const [recaptcha, setRecaptcha] = useState<string>("");

    const rechapcha = () => {
        const props = {
            style: { margin: "auto", width: "304px" },
            sitekey: "6LcDorgaAAAAAGagnT3BKpmwmguuZjW4osBhamI3",
            onChange: (value) => {
                if (value) {
                    setRecaptcha(value);
                }
            },
        };
        // @ts-ignore
        return <ReCAPTCHA {...props} />;
    };

    return (
        <Layout appRoot={appRoot} title="ログイン">
            <main className="main">
                <Box title="ログイン" small={true}>
                    <Row style={{ width: "235px", margin: "auto" }}>
                        <Col>
                            <Form method="GET" action={Url.AUTH_GOOGLE}>
                                <Button type="submit" color="danger">
                                    Googleアカウントでログイン
                                </Button>
                            </Form>
                        </Col>
                    </Row>
                    <Form method="POST" action={Url.LOGIN} id="login-form">
                        <CSRFToken appRoot={appRoot} />
                        <TextInput
                            identity="email"
                            controlType="email"
                            label="メールアドレス"
                            autoFocus={true}
                        />
                        <TextInput
                            identity="password"
                            controlType="password"
                            autoComplete="current-password"
                            label="パスワード"
                        />
                        <FormGroup>
                            {rechapcha()}
                            <input
                                type="hidden"
                                name="g-recaptcha-response"
                                value={recaptcha}
                            />
                        </FormGroup>
                        <FormGroup>
                            <div className="checkbox-wrap text-center">
                                <label>
                                    <Input
                                        type="checkbox"
                                        id="remember"
                                        name="remember"
                                        className="form-check-input"
                                        value="1"
                                    />{" "}
                                    <span>Remember Me</span>
                                </label>
                            </div>
                        </FormGroup>
                        <Row style={{ width: "350px", margin: "auto" }}>
                            <Col>
                                <p className="border">
                                    テスト用ユーザ
                                    <br />
                                    メールアドレス: test1@test.com
                                    <br />
                                    パスワード: password
                                </p>
                            </Col>
                        </Row>
                        <LoginButton />
                    </Form>
                </Box>
            </main>
        </Layout>
    );
};

export default LoginForm;
