type Props = {
  target: string;
  className?: string;
};

const SessionAlert = ({ target, className }: Props) => {
  if (window.laravelSession[target] !== '') {
    return (
      <div className={`bg-gray-100 text-center py-4 lg:px-4 ${className}`}>
        {target === 'resent'
          ? 'あなたのメールアドレスに新しい認証リンクが送信されました。'
          : window.laravelSession[target]}
      </div>
    );
  } else {
    return <></>;
  }
};

export default SessionAlert;
