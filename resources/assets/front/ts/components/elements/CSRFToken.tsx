import React, { FC } from "react";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const CSRFToken: FC<Props> = ({ appRoot }) => {
    const { csrf } = appRoot.auth;
    return <input type="hidden" name="_token" defaultValue={csrf} />;
};

export default CSRFToken;
