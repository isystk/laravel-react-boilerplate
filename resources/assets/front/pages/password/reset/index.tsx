import CSRFToken from '@/components/atoms/CSRFToken';
import SessionAlert from '@/components/atoms/SessionAlert';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';

const ResetForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');
  if (!state) return <></>;

  return (
    <BasicLayout title={t('passwordReset.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <form method="POST" action="/forgot-password" id="login-form">
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <SessionAlert target="status" className="mb-5" />
            <TextInput
              identity="email"
              controlType="email"
              label={t('passwordReset.email')}
              autoFocus={true}
              className="mb-5"
            />
            <div className="mt-3 text-center">
              <button type="submit" className="btn btn-primary mr-5">
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
