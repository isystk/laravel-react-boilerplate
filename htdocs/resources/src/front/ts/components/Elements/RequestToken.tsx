import React, { FC } from "react";
import { Form } from "react-bootstrap";
import { useParams } from "react-router";

const RequestToken: FC = () => {
    const { id } = useParams<{ id: string }>();
    return <Form.Control type="hidden" name="token" defaultValue={id} />;
};

export default RequestToken;
