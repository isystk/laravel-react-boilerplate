import React, { FC } from "react";
import { Row, Col, Button } from "reactstrap";
import { Link } from "react-router-dom";
import { Url } from "@/constants/url";

const LoginButton: FC = () => (
    <Row className="form-group mt-3">
        <Col className="text-center">
            <Button type="submit" color="primary">
                ログイン
            </Button>
            <Link to={Url.PASSWORD_RESET} className="btn btn-link">
                パスワードを忘れた方
            </Link>
        </Col>
    </Row>
);
export default LoginButton;
