import styles from './styles.module.scss';
import BasicLayout from '@/components/templates/BasicLayout';
import { Link } from 'react-router-dom';
import { Url } from '@/constants/url';

type Props = {
  status?: number;
};

const ErrorPage = ({ status = 500 }: Props) => {
  const errors: { [key: number]: { title: string; text: string } } = {
    404: {
      title: '404 - ページが見つかりません',
      text: 'お探しのページは存在しないか、移動されました。',
    },
    500: {
      title: '500 - サーバーエラーが発生しました',
      text: '少し時間を置いて再度お試しください。',
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
            ホームに戻る
          </Link>
        </div>
      </div>
    </BasicLayout>
  );
};

export default ErrorPage;
