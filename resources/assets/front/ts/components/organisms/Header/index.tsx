import React from 'react';
import styles from './styles.module.scss';
import Logo from "@/components/molecules/Logo";
import {Link, useNavigate} from "react-router-dom";
import { Url } from "@/constants/url";
import Image from "@/components/atoms/Image";
import DropDown from "@/components/atoms/DropDown";
import SideMenu from "@/components/organisms/SideMenu";
import useAppRoot from "@/stores/useAppRoot";
import CSRFToken from "@/components/atoms/CSRFToken";

const Header = () => {
    const appRoot = useAppRoot();
    if (!appRoot) return <></>;

    const { isLogined, name } = appRoot.auth;

    const navigate = useNavigate();
    return (
        <header className={`${styles.header} shadow-sm`}>
            <nav className="flex flex-wrap items-center justify-between bg-white px-4 py-3">
                <Logo />
                {/* メニュー（PC表示） */}
                <div className={`${styles.menuContainer} hidden md:flex`}>
                    <ul>
                        {(() => {
                            if (isLogined) {
                                return (
                                    <li>
                                        <DropDown
                                            text={`${name} 様`}
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
                                );
                            } else {
                                return (
                                    <>
                                        <li>
                                            <Link className="btn btn-danger" to={Url.LOGIN}>ログイン</Link>
                                        </li>
                                        <li>
                                            <Link to={Url.REGISTER}>新規登録</Link>
                                        </li>
                                    </>
                                );
                            }
                        })()}
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
                    text={isLogined ? `${name} 様` : ''}
                    items={(() => {
                        let items = [];
                        if (isLogined) {
                            items.push(
                                {
                                    text: "ログアウト",
                                    onClick: () => {
                                        const element: HTMLFormElement = document.getElementById("logout-form") as HTMLFormElement;
                                        if (element) {
                                            element.submit();
                                        }
                                    }
                                }
                            );
                        } else {
                            items.push(
                                {
                                    text: "ログイン",
                                    onClick: () => navigate(Url.LOGIN)
                                },
                                {
                                    text: "新規登録",
                                    onClick: () => navigate(Url.REGISTER)
                                },
                            );
                        }
                        items = items.concat([
                            {
                                text: "カートを見る",
                                onClick: () => navigate(Url.MYCART)
                            },
                            {
                                text: "お問い合わせ",
                                onClick: () => navigate(Url.CONTACT)
                            }
                        ]);
                        return items;
                    })()}
                />
            </nav>
            <form
                id="logout-form"
                action={Url.LOGOUT}
                method="POST"
            >
                <CSRFToken />
            </form>
        </header>
    );
};

export default Header;
