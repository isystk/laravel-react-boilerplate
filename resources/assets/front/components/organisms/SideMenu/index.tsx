import { useState } from 'react';
import styles from './styles.module.scss';
import HamburgerButton from '@/components/atoms/HamburgerButton';

type Props = {
  text: string;
  items: Array<{ text: string; onClick?: () => void }>;
  className?: string;
};

const SideMenu = ({ text, items, className = '' }: Props) => {
  const [isOpen, setOpen] = useState(false);

  return (
    <>
      {/* ハンバーガーメニュー */}
      <HamburgerButton isOpen={isOpen} onClick={setOpen} />
      {/* サイドメニュー */}
      <div className={`${styles.sideMenu} ${className} ${isOpen ? styles.open : styles.closed}`}>
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
