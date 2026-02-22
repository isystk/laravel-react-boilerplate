import { useState } from 'react';
import { Api } from '@/constants/api';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

const ResetForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const [email, setEmail] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [loading, setLoading] = useState(false);

  if (!state) return <></>;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setSuccessMessage('');
    setLoading(true);

    try {
      const { data } = await axios.post(Api.FORGOT_PASSWORD, { email });
      setSuccessMessage(data.message ?? t('passwordReset.sent'));
    } catch (err: any) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors ?? {});
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <BasicLayout title={t('passwordReset.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form onSubmit={handleSubmit} id="forgot-password-form">
          <div className="mx-auto md:w-100">
            {successMessage && (
              <div className="mb-5 p-3 bg-green-100 text-green-700 rounded">{successMessage}</div>
            )}
            <TextInput
              identity="email"
              controlType="email"
              label={t('passwordReset.email')}
              defaultValue={email}
              action={setEmail}
              autoFocus={true}
              className="mb-5"
            />
            {errors.email && <p className="text-red-500 text-sm">{errors.email[0]}</p>}
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5" disabled={loading}>
                {t('passwordReset.submit')}
              </button>
            </div>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default ResetForm;
