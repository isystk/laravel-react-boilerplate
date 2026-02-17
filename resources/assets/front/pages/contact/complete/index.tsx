import { Url } from '@/constants/url';
import { Link } from 'react-router-dom';
import BasicLayout from '@/components/templates/BasicLayout';
import { useTranslation } from 'react-i18next';

const ContactComplete = () => {
  const { t } = useTranslation('contact');

  return (
    <BasicLayout title={t('complete.title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <h2 className="font-bold text-xl text-center">{t('complete.heading')}</h2>
        <div className="text-center mt-10">
          <p className="mt-5">{t('complete.message')}</p>
          <Link to={Url.TOP} className="btn btn-primary mt-10">
            {t('common:backToProducts')}
          </Link>
        </div>
      </div>
    </BasicLayout>
  );
};

export default ContactComplete;
