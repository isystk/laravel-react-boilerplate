import styles from './styles.module.scss';

type Props = {
    title: string,
    className?: string,
};

const SectionTitle = ({ title, className }: Props) => {
    return (
        <>
            <h3 className={`${styles.sectionTitle} ${className}`}>{title}</h3>
            <hr className={styles.hr}/>
        </>
    );
};

export default SectionTitle;

