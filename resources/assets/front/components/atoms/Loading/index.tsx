import styles from './styles.module.scss';
import useAppRoot from '@/states/useAppRoot';
import Portal from '@/components/interactions/Portal';
import { useTranslation } from 'react-i18next';

const Loading = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation();

  if (!state) return <></>;
  const { isLoading } = state;

  return (
    <Portal>
      <>
        {isLoading && (
          <div className={styles.overlay}>
            <div className={styles.spinner}></div>
            <p id="loading" className={styles.message}>
              {t('loading')}
            </p>
          </div>
        )}
      </>
    </Portal>
  );
};

export default Loading;
