import React, { FC } from "react";
import { Link } from "react-router-dom";
import { Url } from "@/constants/url";

export const Logo: FC = () => {
    return (
        <Link className="flex items-center" to={Url.TOP}>
            <img src="/assets/front/image/logo.png" alt="" className="h-10 md:h-auto" />
        </Link>
    );
};

export default Logo;
