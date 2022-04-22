import React, { VFC } from "react";
import { useSelector } from "react-redux";
import Portal from "./Portal";
import { Parts } from "@/stores/StoreTypes";

const Loading: VFC = () => {
    const { isShowLoading } = useSelector(parts);

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

const parts = (state): Parts => state.parts;

export default Loading;
