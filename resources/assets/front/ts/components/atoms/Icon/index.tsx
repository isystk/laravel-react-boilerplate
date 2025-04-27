import styles from './styles.module.scss';

type Props = {
    text: string,
    className?: string,
};

const Icon = ({ text, className }: Props) => {
    return (
        <>
            <span className={`${className} ${styles.icon}`}>
                {text}
            </span>
        </>
    );
};

export default Icon;

