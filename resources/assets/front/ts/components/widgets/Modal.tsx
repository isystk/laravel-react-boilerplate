import React, { FC } from "react";

type Props = {
    children: React.ReactNode;
    isOpen: boolean;
    handleClose: () => void;
};

const Modal: FC<Props> = ({ children, isOpen, handleClose }) => {
    return (
        <>
            {isOpen && <div id="overlay-background"></div>}
            <div className={`isystk-overlay ${isOpen ? "open" : ""}`}>
                <button
                    type="button"
                    className="close"
                    aria-label="Close"
                    onClick={handleClose}
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                {children}
            </div>
        </>
    );
};

export default Modal;
