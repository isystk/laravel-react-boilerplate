import styles from './styles.module.scss';
import BasicLayout from '@/components/templates/BasicLayout';
import { Link } from 'react-router-dom';
import { Url } from '@/constants/url';
import { useTranslation } from 'react-i18next';

type Props = {
  status?: number;
};

const ErrorPage = ({ status = 500 }: Props) => {
  const { t } = useTranslation('error');

  const errors: { [key: number]: { title: string; text: string } } = {
    404: {
      title: t('404.title'),
      text: t('404.text'),
    },
    500: {
      title: t('500.title'),
      text: t('500.text'),
    },
  };

  const { title, text } = errors[status] || errors[500];

  return (
    <BasicLayout title={title}>
      <div className="bg-white h-100 flex items-center justify-center rounded-md shadow-md">
        <div className={styles.container}>
          <h1 className={styles.heading}>{title}</h1>
          <p className={styles.text}>{text}</p>
          <Link to={Url.TOP} className={styles.link}>
            {t('common:backToHome')}
          </Link>
        </div>
      </div>
    </BasicLayout>
  );
};

export default ErrorPage;
