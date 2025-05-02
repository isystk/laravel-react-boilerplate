import styles from './styles.module.scss';
import useAppRoot from '@/stores/useAppRoot';
import Portal from '@/components/interactions/Portal';

const Loading = () => {
  const appRoot = useAppRoot();
  if (!appRoot) return null;

  const { isShowLoading } = appRoot;

  return (
    <Portal>
      <>
        {isShowLoading && (
          <div className={styles.overlay}>
            <div className={styles.spinner}></div>
            <p className={styles.message}>Loading...</p>
          </div>
        )}
      </>
    </Portal>
  );
};

export default Loading;
