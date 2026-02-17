import SessionAlert from '@/components/atoms/SessionAlert';
import BasicLayout from '@/components/templates/BasicLayout';
import { useTranslation } from 'react-i18next';

const Home = () => {
  const { t } = useTranslation('auth');

  return (
    <BasicLayout title={t('home.title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <SessionAlert target="status" />
        {t('home.loginSuccess')}
      </div>
    </BasicLayout>
  );
};
export default Home;
