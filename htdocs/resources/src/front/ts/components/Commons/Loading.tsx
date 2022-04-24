import React, { FC } from "react";
import Portal from "./Portal";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

const Loading: FC<Props> = ({ appRoot }) => {
    const { isShowLoading } = appRoot;
    return (
        <Portal>
            {isShowLoading && (
                <div id="site_loader_overlay">
                    <div className="site_loader_spinner"></div>
                </div>
            )}
        </Portal>
    );
};

export default Loading;
