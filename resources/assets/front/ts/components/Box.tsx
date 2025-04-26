import React, { FC } from "react";

import { Url } from "@/constants/url";
import { Link } from "react-router-dom";

type Props = {
    title: string;
    children: React.ReactNode;
    small?: boolean;
};

const Box: FC<Props> = ({ title, children, small }) => {
    const grids = {
        md: small ? 8 : 12,
    };
    return (
        <>
            <div>
                <div className="justify-content-center">
                    <div {...grids}>
                        <div>
                            <div>
                                <Link to={Url.TOP}>TOP</Link>
                            </div>
                            <div >{title}</div>
                        </div>
                        <div>
                            <div className="pl-md-5">{title}</div>
                            <div className="pl-md-5">{children}</div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default Box;
