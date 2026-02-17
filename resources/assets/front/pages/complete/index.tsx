import { Url } from '@/constants/url';
import { Link } from 'react-router-dom';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import { useTranslation } from 'react-i18next';

const ShopComplete = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('cart');

  if (!state) return <></>;
  const auth = state.auth;

  return (
    <BasicLayout title={t('complete.title')}>
      <div className="bg-white p-6 rounded-md shadow-md ">
        <h2 className="font-bold text-xl text-center">
          {t('complete.thankYou', { name: auth.name })}
        </h2>
        <div className="text-center mt-10">
          <p className="mt-5">
            {t('complete.message1')}
            <br />
            {t('complete.message2')}
          </p>
          <Link to={Url.TOP} className="btn btn-primary mt-10">
            {t('common:backToProducts')}
          </Link>
        </div>
      </div>
    </BasicLayout>
  );
};

export default ShopComplete;
