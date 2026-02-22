import { useState } from 'react';
import { Api } from '@/constants/api';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import { useTranslation } from 'react-i18next';
import axios from 'axios';

const Verify = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  const [successMessage, setSuccessMessage] = useState('');
  const [loading, setLoading] = useState(false);

  if (!state) return <></>;

  const handleResend = async (e: React.MouseEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const { data } = await axios.post(Api.EMAIL_RESEND);
      setSuccessMessage(data.message ?? t('verify.resent'));
    } finally {
      setLoading(false);
    }
  };

  return (
    <BasicLayout title={t('verify.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        {successMessage && (
          <div className="mb-3 p-3 bg-green-100 text-green-700 rounded">{successMessage}</div>
        )}
        {t('verify.message')}{' '}
        <a href="#" onClick={handleResend} style={{ opacity: loading ? 0.5 : 1 }}>
          {t('verify.resendLink')}
        </a>{' '}
        {t('verify.resendSuffix')}
      </div>
    </BasicLayout>
  );
};

export default Verify;
