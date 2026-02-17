import { useState } from 'react';
import CSRFToken from '@/components/atoms/CSRFToken';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';

const RegisterForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const [name, setName] = useState<string>('');
  const [email, setEmail] = useState<string>('');

  if (!state) return <></>;
  return (
    <BasicLayout title={t('register.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form method="POST" action="/register" id="login-form">
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <TextInput
              identity="name"
              controlType="text"
              label={t('register.name')}
              defaultValue={name}
              action={setName}
              autoFocus={true}
              className="mb-5"
            />
            <TextInput
              identity="email"
              controlType="email"
              label={t('register.email')}
              defaultValue={email}
              action={setEmail}
              className="mb-5"
            />
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="new-password"
              label={t('register.password')}
              className="mb-5"
            />
            <TextInput
              identity="password_confirmation"
              controlType="password"
              autoComplete="new-password"
              label={t('register.passwordConfirm')}
              className="mb-5"
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5">
                {t('register.submit')}
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default RegisterForm;
