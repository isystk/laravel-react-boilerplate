import styles from './styles.module.scss';
import Logo from "@/components/molecules/Logo";
import {Link} from "react-router-dom";
import { Url } from "@/constants/url";
import Image from "@/components/atoms/Image";

const Header = () => {
    return (
        <header className={`${styles.header} shadow-sm`}>
            <nav className="flex flex-wrap items-center justify-between bg-white px-4 py-3">
                {/* ロゴ */}
                <Logo />
                {/* メニュー（PC表示） */}
                <div className={`${styles.menuContainer} hidden md:flex`}>
                    <ul>
                        <li>
                            <Link
                                className="btn btn-danger"
                                to={Url.LOGIN}
                            >
                                ログイン
                            </Link>
                        </li>
                        <li>
                            <div className="dropdown">
                                <button id="dropdownToggle" className="dropdown-toggle">テスト1 様</button>
                                <div id="dropdownMenu" className="dropdown-menu hidden">
                                    <button type="button" className="dropdown-item">ログアウト</button>
                                    <form action="#" method="POST" className="hidden"></form>
                                    <button type="button" className="dropdown-item">カートを見る</button>
                                </div>
                            </div>
                        </li>
                        <li>
                            <Link to={Url.REGISTER}>新規登録</Link>
                        </li>
                        <li>
                            <Link
                                to={Url.MYCART}
                            >
                                <Image src="https://localhost/assets/front/image/cart.png" width={30} height={30} alt="カート"/>
                            </Link>
                        </li>
                        <li>
                            <Link to={Url.CONTACT}>お問い合わせ</Link>
                        </li>
                    </ul>
                </div>

                {/* ハンバーガーメニュー */}
                <div className="hidden menu-btn flex flex-col justify-center space-y-1.5 ml-4 md:hidden cursor-pointer">
                    <span className="block w-6 h-0.5 bg-black"></span>
                    <span className="block w-6 h-0.5 bg-black"></span>
                    <span className="block w-6 h-0.5 bg-black"></span>
                </div>
                {/* サイドメニュー */}
                <div
                    className="hidden fixed inset-y-0 right-0 w-64 bg-white shadow-lg z-40 transform translate-x-full transition-transform duration-300"
                >
                    <div className="p-5 text-lg font-medium border-b">
                        <p className="text-gray-700"></p>
                    </div>
                    <nav>
                        <ul className="flex flex-col p-4 space-y-2">
                            <li>
                                <a
                                    href="./login.html"
                                    className="block text-gray-800 hover:text-red-600"
                                >ログイン
                                </a>
                            </li>
                            <li>
                                <a
                                    href="./register.html"
                                    className="block text-gray-800 hover:text-red-600"
                                >新規登録
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    className="block text-gray-800 hover:text-red-600"
                                >ログアウト
                                </a>
                                <form
                                    id="logout-form"
                                    action="https://localhost/logout"
                                    method="POST"
                                    className="hidden"
                                ></form>
                            </li>
                            <li>
                                <a
                                    href="./mycart.html"
                                    className="block text-gray-800 hover:text-red-600"
                                >カートを見る
                                </a>
                                <form
                                    id="mycart-form"
                                    action="https://localhost/mycart"
                                    method="POST"
                                    className="hidden"
                                ></form>
                            </li>
                            <li>
                                <a
                                    href="./contact.html"
                                    className="block text-gray-800 hover:text-red-600"
                                >お問い合わせ
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                {/* オーバーレイ */}
                <div
                    id="layer-panel"
                    className="hidden fixed inset-0 bg-black bg-opacity-50 z-30"
                ></div>
            </nav>
        </header>
    );
};

export default Header;
