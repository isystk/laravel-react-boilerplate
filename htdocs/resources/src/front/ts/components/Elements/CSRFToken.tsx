import React, { VFC } from "react";
import { Form } from "react-bootstrap";
import { useSelector } from "react-redux";
import { Auth } from "@/stores/StoreTypes";

type IRoot = {
    auth: Auth;
};

const CSRFToken: VFC = () => {
    const { csrf } = useSelector<IRoot, Auth>(state => state.auth);
    return <Form.Control type="hidden" name="_token" defaultValue={csrf} />;
};

export default CSRFToken;
