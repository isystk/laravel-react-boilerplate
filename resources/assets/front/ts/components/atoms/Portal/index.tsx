import {useRef, useState, useEffect, ReactNode} from "react";
import ReactDOM from "react-dom";

type Props = {
    children: ReactNode;
};

const Portal = ({ children }: Props) => {
    const ref = useRef<HTMLElement>(null);
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
