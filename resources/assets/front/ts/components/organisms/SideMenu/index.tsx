import { FC, useState } from "react";
import styles from './styles.module.scss';

type Props = {
    text: string,
    items: Array<{ text: string, onClick?: () => void }>,
    className?: string
};

const SideMenu: FC<Props> = ({ text, items, className = '' }: Props) => {
    const [isOpen, setOpen] = useState(false);

    return (
        <>
            {/* ハンバーガーメニュー */}
            <div
                className={styles.menuBtn}
                onClick={() => setOpen(!isOpen)}
            >
                <span></span>
                <span></span>
                <span></span>
            </div>
            {/* サイドメニュー */}
            <div
                className={`${styles.sideMenu} ${className} ${isOpen ? styles.open : styles.closed}`}
            >
                <div className={styles.menuHeader}>
                    <p>{text}</p>
                </div>
                <nav>
                    <ul className={styles.menuList}>
                        {items.map(({ text, onClick }, index) => (
                            <li key={index}>
                                <a
                                    className={styles.menuItem}
                                    onClick={() => {
                                        onClick?.();
                                        setOpen(false);
                                    }}
                                >
                                    {text}
                                </a>
                            </li>
                        ))}
                    </ul>
                </nav>
            </div>
            {/* オーバーレイ */}
            <div
                className={`${styles.overlay} ${isOpen ? styles.visible : styles.hidden}`}
                onClick={() => setOpen(!isOpen)}
            ></div>
        </>
    );
};

export default SideMenu;
