import { useEffect, useState } from 'react';
import styles from './styles.module.scss';

type Props = {
  message?: string | null;
};

const FlashMessage = (props: Props) => {
  const [fadeOut, setFadeOut] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [message, setMessage] = useState(props.message);

  useEffect(() => {
    const laravelMessage = window.laravelSession['status'];
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
  window.laravelSession['status'] = '';

  const handleAnimationEnd = () => {
    if (fadeOut) {
      setHidden(true);
    }
  };

  return (
    <div
      className={`${styles.flashMessage} ${fadeOut ? styles.fadeOut : ''} ${hidden ? styles.hidden : ''}`}
      onAnimationEnd={handleAnimationEnd}
    >
      {message}
    </div>
  );
};

export default FlashMessage;
