import styles from './styles.module.scss';
import Logo from "@/components/molecules/Logo";
import {Link, useNavigate} from "react-router-dom";
import { Url } from "@/constants/url";
import Image from "@/components/atoms/Image";
import DropDown from "@/components/atoms/DropDown";
import SideMenu from "@/components/atoms/SideMenu";

const Header = () => {
    const navigate = useNavigate();
    return (
        <header className={`${styles.header} shadow-sm`}>
            <nav className="flex flex-wrap items-center justify-between bg-white px-4 py-3">
                {/* ロゴ */}
                <Logo />
                {/* メニュー（PC表示） */}
                <div className={`${styles.menuContainer} hidden md:flex`}>
                    <ul>
                        <li>
                            <Link className="btn btn-danger" to={Url.LOGIN}>ログイン</Link>
                        </li>
                        <li>
                            <DropDown
                                text={"テスト 様"}
                                items={[
                                    {
                                        text: "ログアウト",
                                        onClick: () => {
                                            const element: HTMLFormElement = document.getElementById("logout-form") as HTMLFormElement;
                                            if (element) {
                                                element.submit();
                                            }
                                        }
                                    },
                                    {
                                        text: "カートを見る",
                                        onClick: () => navigate(Url.MYCART)
                                    }
                                ]}
                            />
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

                {/* サイドメニュー */}
                <SideMenu
                    items={[
                        {
                            text: "ログイン",
                            onClick: () => navigate(Url.LOGIN)
                        },
                        {
                            text: "新規登録",
                            onClick: () => navigate(Url.REGISTER)
                        },
                        {
                            text: "ログアウト",
                            onClick: () => {
                                const element: HTMLFormElement = document.getElementById("logout-form") as HTMLFormElement;
                                if (element) {
                                    element.submit();
                                }
                            }
                        },
                        {
                            text: "カートを見る",
                            onClick: () => navigate(Url.MYCART)
                        },
                        {
                            text: "お問い合わせ",
                            onClick: () => navigate(Url.CONTACT)
                        }
                    ]}
                />
            </nav>
        </header>
    );
};

export default Header;
