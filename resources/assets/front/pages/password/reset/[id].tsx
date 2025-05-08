import { useState } from 'react';
import CSRFToken from '@/components/atoms/CSRFToken';
import SessionAlert from '@/components/atoms/SessionAlert';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useParams } from 'react-router-dom';

const ResetForm = () => {
  const { state } = useAppRoot();
  const { token } = useParams<{ token: string }>();
  const [email, setEmail] = useState<string>('');

  const handleSetEmail = (value: string) => {
    setEmail(value);
  };

  if (!state) return <></>;

  return (
    <BasicLayout title="パスワード変更">
      <div className="bg-white p-6 rounded-md shadow-md ">
        <SessionAlert target="status" />
        <form method="POST" action="/reset-password" id="login-form">
          <CSRFToken />
          <input type="hidden" name="token" value={token || ''} />;
          <div className="mx-auto md:w-100">
            <TextInput
              identity="email"
              controlType="email"
              label="メールアドレス"
              defaultValue={email}
              action={handleSetEmail}
              autoFocus={true}
              className="mb-5"
            />
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="new-password"
              label="新しいパスワード"
              className="mb-5"
            />
            <TextInput
              identity="password_confirmation"
              controlType="password"
              autoComplete="new-password"
              label="新しいパスワード(確認)"
              className="mb-5"
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5">
                パスワードを変更する
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default ResetForm;
