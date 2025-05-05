import { useEffect, useState } from 'react';
import styles from './styles.module.scss';
import Portal from '@/components/interactions/Portal';

export const MessageTypes = {
  Success: 'success',
  Error: 'error',
} as const;

type Props = {
  type?: (typeof MessageTypes)[keyof typeof MessageTypes];
  message?: string | null;
};

const FlashMessage = ({ type = MessageTypes.Success, ...props }: Props) => {
  const [fadeOut, setFadeOut] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [message, setMessage] = useState(props.message);

  useEffect(() => {
    const laravelMessage = window.laravelSession?.status;
    if (!message && laravelMessage) {
      setMessage(laravelMessage);
      return;
    }

    if (message) {
      const timer = setTimeout(() => {
        setFadeOut(true);
      }, 5000);

      return () => clearTimeout(timer);
    }
    return;
  }, [message]);

  if (!message) {
    return <></>;
  }
  if (window.laravelSession && typeof window.laravelSession === 'object') {
    window.laravelSession['status'] = '';
  }

  const handleAnimationEnd = () => {
    if (fadeOut) {
      setHidden(true);
    }
  };

  return (
    <Portal>
      <div
        className={`
          ${styles.flashMessage}
          ${fadeOut ? styles.fadeOut : ''}
          ${hidden ? styles.hidden : ''}
          ${type === MessageTypes.Error ? styles.error : ''}
        `}
        onAnimationEnd={handleAnimationEnd}
      >
        {message}
      </div>
    </Portal>
  );
};

export default FlashMessage;
