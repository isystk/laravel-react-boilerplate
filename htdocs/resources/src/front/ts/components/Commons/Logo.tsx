import React, { FC } from "react";
import { Url } from "../../constants/url";

export const Logo: FC = () => {
    return (
        <a className="header_logo" href={Url.TOP}>
            <img src="/assets/front/image/logo.png" alt="" />
        </a>
    );
};

export default Logo;
