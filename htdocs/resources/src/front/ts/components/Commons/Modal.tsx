import React, { FC } from "react";
import { useDispatch, useSelector } from "react-redux";
import Portal from "./Portal";
import { Parts } from "@/stores/StoreTypes";
import { hideOverlay } from "@/services/actions";

type IRoot = {
    parts: Parts;
};

type Props = {
    children: React.ReactNode;
};

const Modal: FC<Props> = ({ children }) => {
    const dispatch = useDispatch();
    const { isShowOverlay } = useSelector<IRoot, Parts>(
        (state): Parts => state.parts
    );

    const onClose = e => {
        e.preventDefault();
        dispatch(hideOverlay());
    };

    return (
        <Portal>
            {isShowOverlay && <div id="overlay-background"></div>}
            <div className={`isystk-overlay ${isShowOverlay ? "open" : ""}`}>
                <button
                    type="button"
                    className="close"
                    aria-label="Close"
                    onClick={onClose}
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                {children}
            </div>
        </Portal>
    );
};

export default Modal;
