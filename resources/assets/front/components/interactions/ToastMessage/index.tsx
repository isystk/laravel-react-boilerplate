import styles from './styles.module.scss';
import Modal from '@/components/interactions/Modal';
import { useEffect, useRef } from 'react';
import { useTranslation } from 'react-i18next';

export const ToastTypes = {
  Alert: 'alert',
  Confirm: 'confirm',
} as const;

export type ToastType = (typeof ToastTypes)[keyof typeof ToastTypes];

type ToastMessageProps = {
  isOpen: boolean;
  type?: ToastType;
  title?: string;
  message: string;
  confirmText?: string;
  cancelText?: string;
  confirmClass?: string;
  cancelClass?: string;
  onConfirm?: () => void;
  onCancel?: () => void;
};

const ToastMessage = ({
  isOpen,
  type = ToastTypes.Alert,
  title = '',
  message,
  confirmText,
  cancelText,
  confirmClass = 'btn btn-primary',
  cancelClass = 'btn btn-secondary',
  onConfirm: onPropConfirm,
  onCancel: onPropCancel,
}: ToastMessageProps) => {
  const { t } = useTranslation();
  const cancelButtonRef = useRef<HTMLButtonElement>(null);

  const displayConfirmText = confirmText || t('toast.yes');
  const displayCancelText = cancelText || t('toast.no');

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
        <div className={styles.header}>
          <h5 className="text-lg font-bold">{title}</h5>
        </div>
        <div className={styles.body}>
          <p className={styles.message}>{message}</p>
        </div>
        <div className={styles.footer}>
          <div className={styles.buttonGroup}>
            <button className={confirmClass} onClick={onConfirm}>
              {displayConfirmText}
            </button>
            {type === ToastTypes.Confirm && (
              <button className={cancelClass} onClick={onCancel} ref={cancelButtonRef}>
                {displayCancelText}
              </button>
            )}
          </div>
        </div>
      </>
    </Modal>
  );
};

export { ToastMessage };
