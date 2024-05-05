import React, { FC } from "react";
import MainService from "@/services/main";
import { Spinner } from "reactstrap";
import Portal from "@/components/commons/Portal";

type Props = {
    appRoot: MainService;
};

const Loading: FC<Props> = ({ appRoot }) => {
    const { isShowLoading } = appRoot;
    return (
        <Portal>
            {isShowLoading && (
                <div id="site_loader_overlay">
                    <div className="site_loader_spinner">
                        <Spinner>Loading...</Spinner>
                    </div>
                </div>
            )}
        </Portal>
    );
};

export default Loading;
