import React, { FC, useState } from "react";

import { Form, Input, Col, Row } from "reactstrap";
import TextInput from "@/components/Elements/TextInput";
import LoginButton from "@/components/Elements/LoginButton";
import CSRFToken from "@/components/Elements/CSRFToken";
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
            onChange: value => {
                if (value) {
                    setRecaptcha(value);
                }
            }
        };
        // @ts-ignore
        return <ReCAPTCHA {...props} />;
    };

    return (
        <Layout appRoot={appRoot} title="ログイン">
            <main className="main">
                <Box title="ログイン">
                    <div className="text-center mb-3  ">
                        <form method="GET" action={Url.AUTH_GOOGLE}>
                            <button type="submit" className="btn btn-danger">
                                Googleアカウントでログイン
                            </button>
                        </form>
                    </div>
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
                        <Row className="text-center form-group mt-3">
                            <Col>
                                {rechapcha()}
                                <input
                                    type="hidden"
                                    name="g-recaptcha-response"
                                    value={recaptcha}
                                />
                            </Col>
                        </Row>
                        <Row className="text-center form-group mt-3">
                            <Col>
                                <div
                                    className="form-section"
                                    style={{ display: "block" }}
                                >
                                    <div className="checkbox-wrap">
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
                                </div>
                                <p className="fz-s">
                                    email: test1@test.com
                                    <br />
                                    password: password
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
