import styles from './styles.module.scss';
import { useEffect, useState } from 'react';

export type Props = {
  isOpen: boolean;
  onClick: (isOpen: boolean) => void;
  className?: string;
};

const HamburgerButton = (props: Props) => {
  const [isOpen, setOpen] = useState(props.isOpen);

  useEffect(() => {
    setOpen(props.isOpen);
  }, [props.isOpen]);

  const handleClick = () => {
    const open = !isOpen;
    setOpen(open);
    props.onClick(open);
  };

  return (
    <div
      className={`${styles.menuBtn} ${isOpen ? styles.open : ''} ${isOpen ? 'open' : ''} ${props.className}`}
      onClick={handleClick}
    >
      <span></span>
      <span></span>
      <span></span>
    </div>
  );
};

export default HamburgerButton;
