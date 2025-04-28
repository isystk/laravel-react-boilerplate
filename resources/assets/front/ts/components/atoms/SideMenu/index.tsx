import { FC, useState } from "react";
import styles from './styles.module.scss';

type Props = {
    items: Array<{ text: string, onClick?: () => void }>,
    className?: string
};

const SideMenu: FC<Props> = ({ items, className = '' }: Props) => {
    const [isOpen, setOpen] = useState(false);

    return (
        <>
            {/* ハンバーガーメニュー */}
            <div
                className={`${styles.menuBtn}`}
                onClick={() => setOpen(!isOpen)}
            >
                <span></span>
                <span></span>
                <span></span>
            </div>
            {/* サイドメニュー */}
            <div
                className={`${styles.sideMenu} ${className}  ${isOpen ? 'translate-x-0' : 'hidden translate-x-full '} fixed inset-y-0 right-0 w-64 bg-white shadow-lg z-40 transform transition-transform duration-300`}
            >
                <div className="p-5 text-lg font-medium border-b">
                    <p className="text-gray-700"></p>
                </div>
                <nav>
                    <ul className="flex flex-col p-4 space-y-2">
                        {items.map(({ text, onClick }, index) => (
                            <li key={index}>
                                <a
                                    className="block p-2 rounded hover:bg-gray-100 cursor-pointer"
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
                className={`${isOpen ? '' : 'hidden'} fixed inset-0 bg-black bg-opacity-50 z-30`}
                onClick={() => setOpen(!isOpen)}
            ></div>
        </>
    );
};

export default SideMenu;
