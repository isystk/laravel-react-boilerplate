import React, { FC } from "react";
import { Input } from "reactstrap";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const CSRFToken: FC<Props> = ({ appRoot }) => {
    const { csrf } = appRoot.auth;
    return <Input type="hidden" name="_token" defaultValue={csrf} />;
};

export default CSRFToken;
