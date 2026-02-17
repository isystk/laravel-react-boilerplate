import SessionAlert from '@/components/atoms/SessionAlert';
import CSRFToken from '@/components/atoms/CSRFToken';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import { useTranslation } from 'react-i18next';

const Verify = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');

  if (!state) return <></>;

  return (
    <BasicLayout title={t('verify.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <SessionAlert target="resent" />
        {t('verify.message')}{' '}
        <a
          href="#"
          onClick={e => {
            e.preventDefault();
            const form = document.getElementById('email-form') as HTMLFormElement;
            const evt = document.createEvent('Event');
            evt.initEvent('submit', true, true);
            if (form && form.dispatchEvent(evt)) {
              form.submit();
            }
          }}
        >
          {t('verify.resendLink')}
        </a>{' '}
        {t('verify.resendSuffix')}
        <form
          id="email-form"
          action="/email/verification-notification"
          method="POST"
          style={{ display: 'none' }}
        >
          <CSRFToken />
        </form>
      </div>
    </BasicLayout>
  );
};

export default Verify;
