import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

type Props = {
  target: string;
  className?: string;
};

const SessionAlert = ({ target, className }: Props) => {
  const { t } = useTranslation('auth');
  const [message, setMessage] = useState<string | undefined>(undefined);

  useEffect(() => {
    const laravelMessage = window.laravelSession?.[target];
    if (!message && laravelMessage) {
      setMessage(laravelMessage);
    }
  }, [target, message]);

  if (!message) {
    return <></>;
  }

  if (window.laravelSession && typeof window.laravelSession === 'object') {
    window.laravelSession[target] = '';
  }

  return (
    <div className={`bg-gray-100 text-center py-4 lg:px-4 ${className}`}>
      {target === 'resent' ? t('verify.resentMessage') : message}
    </div>
  );
};

export default SessionAlert;
