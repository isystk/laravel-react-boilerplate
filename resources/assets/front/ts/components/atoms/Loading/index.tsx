import styles from './styles.module.scss';
import useAppRoot from '@/stores/useAppRoot';
import Portal from '@/components/interactions/Portal';

const Loading = () => {
  const [state, service] = useAppRoot();
  if (!state) return null;

  const { isShowLoading } = state;

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
