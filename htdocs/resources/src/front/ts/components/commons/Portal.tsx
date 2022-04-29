import ReactDOM from "react-dom";
import React, { FC, useRef, useState, useEffect } from "react";

type Props = {
    children: React.ReactNode;
};

const Portal: FC<Props> = ({ children }) => {
    const ref = useRef<HTMLElement>();
    const [mounted, setMounted] = useState(false);

    useEffect(() => {
        const current = document.querySelector<HTMLElement>("#react-root");
        if (current) {
            ref.current = current;
        }
        setMounted(true);
    }, []);

    return mounted
        ? ReactDOM.createPortal(
              <>{children}</>,
              ref.current ? ref.current : new Element()
          )
        : null;
};

export default Portal;
