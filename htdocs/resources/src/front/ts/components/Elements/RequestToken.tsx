import React, { FC } from "react";
import { Input } from "reactstrap";
import { useParams } from "react-router";

const RequestToken: FC = () => {
    const { id } = useParams<{ id: string }>();
    return <Input type="hidden" name="token" defaultValue={id} />;
};

export default RequestToken;
