import { useState } from 'react';
import { Api } from '@/constants/api';
import { Url } from '@/constants/url';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import { Link } from 'react-router-dom';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

const LoginForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [remember, setRemember] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [loading, setLoading] = useState(false);

  if (!state) return <></>;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setLoading(true);

    try {
      await axios.post(Api.LOGIN, { email, password, remember });
      window.location.href = Url.HOME;
    } catch (err: any) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors ?? {});
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <BasicLayout title={t('login.title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <div className="text-center mb-3">
          <form method="GET" action={Url.AUTH_GOOGLE}>
            <button type="submit" className="btn btn-danger">
              {t('login.googleLogin')}
            </button>
          </form>
        </div>
        <form onSubmit={handleSubmit} id="login-form">
          <div className="mx-auto md:w-100">
            <TextInput
              identity="email"
              controlType="email"
              label={t('login.email')}
              autoFocus={true}
              className="mb-5 md:w-100"
              defaultValue={email}
              action={setEmail}
            />
            {errors.email && <p className="text-red-500 text-sm">{errors.email[0]}</p>}
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="current-password"
              label={t('login.password')}
              className="mb-5 md:w-100"
              action={setPassword}
            />
            {errors.password && <p className="text-red-500 text-sm">{errors.password[0]}</p>}
            <p>
              <input
                type="checkbox"
                id="remember"
                name="remember"
                checked={remember}
                onChange={e => setRemember(e.target.checked)}
              />{' '}
              <span>{t('login.rememberMe')}</span>
            </p>
          </div>
          <div className="mx-auto my-5 border p-3 md:w-100">
            Test User
            <br />
            Email: user1@test.com
            <br />
            Password: password
          </div>
          <div className="mt-3 text-center">
            <button type="submit" className="btn btn-primary mr-5" disabled={loading}>
              {t('login.submit')}
            </button>
            <Link to={Url.PASSWORD_RESET} className="btn">
              {t('login.forgotPassword')}
            </Link>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default LoginForm;
