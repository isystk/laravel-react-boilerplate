import React, { VFC, useState } from "react";

import { Form, Input, Col, Row } from "reactstrap";
import TextInput from "@/components/Elements/TextInput";
import LoginButton from "@/components/Elements/LoginButton";
import CSRFToken from "@/components/Elements/CSRFToken";
import ReCAPTCHA from "react-google-recaptcha";
import Box from "@/components/Box";
import Layout from "@/components/Layout";

const LoginForm: VFC = () => {
    const [recaptcha, setRecaptcha] = useState<string>("");

    return (
        <Layout>
            <main className="main">
                <Box title="ログイン">
                    <div className="text-center mb-3  ">
                        <form method="GET" action="/auth/google">
                            <button type="submit" className="btn btn-danger">
                                Googleアカウントでログイン
                            </button>
                        </form>
                    </div>
                    <Form method="POST" action="/login" id="login-form">
                        <CSRFToken />
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
                                <ReCAPTCHA
                                    style={{
                                        margin: "auto",
                                        width: "304px"
                                    }}
                                    sitekey="6LcDorgaAAAAAGagnT3BKpmwmguuZjW4osBhamI3"
                                    onChange={value => {
                                        if (value) {
                                            setRecaptcha(value);
                                        }
                                    }}
                                />
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
