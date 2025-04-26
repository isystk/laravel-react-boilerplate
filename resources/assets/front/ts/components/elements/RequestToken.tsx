import React, { FC } from "react";
import { useParams } from "react-router";

const RequestToken: FC = () => {
    const { id } = useParams<{ id: string }>();
    return <input type="hidden" name="token" defaultValue={id} />;
};

export default RequestToken;
