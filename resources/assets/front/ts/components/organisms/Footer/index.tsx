import styles from './styles.module.scss';
import Logo from "@/components/molecules/Logo";

const Footer = () => {
    return (
        <footer className={styles.footer}>
            <div className={styles.footerInner}>
                <Logo />
                <p className={styles.footerCopy}>©️isystk このページは架空のページです。実際の人物・団体とは関係ありません。</p>
            </div>
        </footer>
    );
};

export default Footer;

