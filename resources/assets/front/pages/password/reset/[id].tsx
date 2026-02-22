import { useState } from 'react';
import { Api } from '@/constants/api';
import { Url } from '@/constants/url';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useParams, useNavigate, useSearchParams } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

const ResetForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const { token } = useParams<{ token: string }>();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const [email, setEmail] = useState(searchParams.get('email') ?? '');
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
      await axios.post(Api.RESET_PASSWORD, {
        token,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      navigate(Url.LOGIN);
    } catch (err: any) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors ?? {});
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <BasicLayout title={t('passwordChange.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form onSubmit={handleSubmit} id="reset-password-form">
          <div className="mx-auto md:w-100">
            <TextInput
              identity="email"
              controlType="email"
              label={t('passwordChange.email')}
              defaultValue={email}
              action={setEmail}
              autoFocus={true}
              className="mb-5"
            />
            {errors.email && <p className="text-red-500 text-sm">{errors.email[0]}</p>}
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="new-password"
              label={t('passwordChange.newPassword')}
              className="mb-5"
              action={setPassword}
            />
            {errors.password && <p className="text-red-500 text-sm">{errors.password[0]}</p>}
            <TextInput
              identity="password_confirmation"
              controlType="password"
              autoComplete="new-password"
              label={t('passwordChange.newPasswordConfirm')}
              className="mb-5"
              action={setPasswordConfirmation}
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5" disabled={loading}>
                {t('passwordChange.submit')}
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default ResetForm;
