import { useEffect, useState } from 'react';

type Props = {
  target: string;
  className?: string;
};

const SessionAlert = ({ target, className }: Props) => {
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
      {target === 'resent' ? 'あなたのメールアドレスに新しい認証リンクが送信されました。' : message}
    </div>
  );
};

export default SessionAlert;
