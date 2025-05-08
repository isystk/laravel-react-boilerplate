import { useState } from 'react';
import CSRFToken from '@/components/atoms/CSRFToken';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';

const RegisterForm = () => {
  const { state } = useAppRoot();
  const [name, setName] = useState<string>('');
  const [email, setEmail] = useState<string>('');

  if (!state) return <></>;
  return (
    <BasicLayout title="会員登録">
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form method="POST" action="/register" id="login-form">
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <TextInput
              identity="name"
              controlType="text"
              label="お名前"
              defaultValue={name}
              action={setName}
              autoFocus={true}
              className="mb-5"
            />
            <TextInput
              identity="email"
              controlType="email"
              label="メールアドレス"
              defaultValue={email}
              action={setEmail}
              className="mb-5"
            />
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="new-password"
              label="パスワード"
              className="mb-5"
            />
            <TextInput
              identity="password_confirmation"
              controlType="password"
              autoComplete="new-password"
              label="パスワード（確認）"
              className="mb-5"
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5">
                新規登録
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default RegisterForm;
