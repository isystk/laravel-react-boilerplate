import React, { FC } from "react";
import { Form } from "react-bootstrap";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const CSRFToken: FC<Props> = ({ appRoot }) => {
    const { csrf } = appRoot.auth;
    return <Form.Control type="hidden" name="_token" defaultValue={csrf} />;
};

export default CSRFToken;
