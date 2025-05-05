import styles from './styles.module.scss';
import useAppRoot from '@/states/useAppRoot';
import Portal from '@/components/interactions/Portal';

const Loading = () => {
  const [state] = useAppRoot();
  if (!state) return null;

  const { isLoading } = state;

  return (
    <Portal>
      <>
        {isLoading && (
          <div className={styles.overlay}>
            <div className={styles.spinner}></div>
            <p id="loading" className={styles.message}>
              Loading...
            </p>
          </div>
        )}
      </>
    </Portal>
  );
};

export default Loading;
