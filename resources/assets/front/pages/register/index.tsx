import { useState } from 'react';
import { Api } from '@/constants/api';
import { Url } from '@/constants/url';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

const RegisterForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const navigate = useNavigate();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [loading, setLoading] = useState(false);

  if (!state) return <></>;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setLoading(true);

    try {
      await axios.post(Api.REGISTER, {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      navigate(Url.EMAIL_VERIFY);
    } catch (err: any) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors ?? {});
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <BasicLayout title={t('register.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form onSubmit={handleSubmit} id="register-form">
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
            {errors.name && <p className="text-red-500 text-sm">{errors.name[0]}</p>}
            <TextInput
              identity="email"
              controlType="email"
              label={t('register.email')}
              defaultValue={email}
              action={setEmail}
              className="mb-5"
            />
            {errors.email && <p className="text-red-500 text-sm">{errors.email[0]}</p>}
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="new-password"
              label={t('register.password')}
              className="mb-5"
              action={setPassword}
            />
            {errors.password && <p className="text-red-500 text-sm">{errors.password[0]}</p>}
            <TextInput
              identity="password_confirmation"
              controlType="password"
              autoComplete="new-password"
              label={t('register.passwordConfirm')}
              className="mb-5"
              action={setPasswordConfirmation}
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5" disabled={loading}>
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
