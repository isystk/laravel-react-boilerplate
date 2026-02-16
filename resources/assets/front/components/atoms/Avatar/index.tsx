import styles from './styles.module.scss';

type Props = {
  src: string | null;
  size?: number;
  className?: string;
};

const Avatar = ({ src, size = 32, className = '' }: Props) => {
  return (
    <div
      className={`${styles.avatarContainer} ${className}`}
      style={{ width: size, height: size }}
    >
      {src ? (
        <img
          src={src}
          alt="Avatar"
          className={styles.avatarImage}
          style={{ width: size, height: size }}
        />
      ) : (
        <div className={styles.placeholder} style={{ width: size, height: size }}>
          <span className={styles.placeholderIcon}>👤</span>
        </div>
      )}
    </div>
  );
};

export default Avatar;
