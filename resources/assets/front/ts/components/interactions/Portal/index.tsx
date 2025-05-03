import { ReactNode, useEffect, useRef } from 'react';
import { createPortal } from 'react-dom';

type Props = {
  children: ReactNode;
};

const Portal = ({ children }: Props) => {
  const elRef = useRef<HTMLElement | null>(null);

  if (typeof document !== 'undefined' && !elRef.current) {
    elRef.current = document.createElement('div');
  }

  useEffect(() => {
    if (!elRef.current) return;
    document.body.appendChild(elRef.current);
    return () => {
      if (elRef.current?.parentNode) {
        if ('parentNode' in elRef.current) {
          elRef.current.parentNode.removeChild(elRef.current);
        }
      }
    };
  }, []);

  if (typeof document === 'undefined' || !elRef.current) return null;

  return createPortal(children, elRef.current);
};

export default Portal;
