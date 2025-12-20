import styles from './styles.module.scss';
import Modal from '@/components/interactions/Modal';
import { useEffect, useRef } from 'react';

export const ToastTypes = {
  Alert: 'alert',
  Confirm: 'confirm',
} as const;

export type ToastType = (typeof ToastTypes)[keyof typeof ToastTypes];

type ToastMessageProps = {
  isOpen: boolean;
  type?: ToastType;
  message: string;
  onConfirm?: () => void;
  onCancel?: () => void;
};

const ToastMessage = ({
  isOpen,
  type = ToastTypes.Alert,
  message,
  onConfirm: onPropConfirm,
  onCancel: onPropCancel,
}: ToastMessageProps) => {
  const cancelButtonRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    setTimeout(() => {
      cancelButtonRef.current?.focus();
    }, 0);
  }, []);

  const onConfirm = () => {
    onPropConfirm?.();
  };
  const onCancel = () => {
    onPropCancel?.();
  };

  return (
    <Modal isOpen={isOpen} onClose={onCancel} small={true}>
      <>
        <p className={styles.message}>{message}</p>
        <div className={styles.buttonGroup}>
          <button className="btn btn-primary" onClick={onConfirm}>
            はい
          </button>
          {type === ToastTypes.Confirm && (
            <button className="btn btn-danger" onClick={onCancel} ref={cancelButtonRef}>
              いいえ
            </button>
          )}
        </div>
      </>
    </Modal>
  );
};

export { ToastMessage };
