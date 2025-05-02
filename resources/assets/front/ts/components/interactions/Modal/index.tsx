import styles from './styles.module.scss';
import Portal from '@/components/interactions/Portal';
import { JSX } from 'react';

type Props = {
  isOpen: boolean;
  onClose: () => void;
  children: JSX.Element;
};

const Modal = ({ isOpen, onClose, children }: Props) => {
  if (!isOpen) return null;

  return (
    <Portal>
      <div className={styles.overlay} onClick={onClose}>
        <div className={styles.modal} onClick={e => e.stopPropagation()}>
          <button type="button" aria-label="Close" onClick={onClose} className={styles.closeButton}>
            <span aria-hidden="true">×</span>
          </button>
          <div className={styles.content}>{children}</div>
        </div>
      </div>
    </Portal>
  );
};

export default Modal;
