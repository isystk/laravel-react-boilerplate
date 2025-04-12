import React, { FC } from "react";
import { Link } from "react-router-dom";
import { Url } from "@/constants/url";

export const Logo: FC = () => {
    return (
        <Link className="header_logo" to={Url.TOP}>
            <img src="/assets/front/image/logo.png" alt="" />
        </Link>
    );
};

export default Logo;
