import React, { FC, useState } from "react";
import {
    Dropdown,
    DropdownToggle,
    DropdownMenu,
    DropdownItem,
    Form,
} from "reactstrap";
import CSRFToken from "@/components/elements/CSRFToken";
import { Url } from "@/constants/url";
import Logo from "@/components/commons/Logo";
import { Link, useNavigate } from "react-router-dom";
import MainService from "@/services/main";

type Props = {
    appRoot: MainService;
};

export const CommonHeader: FC<Props> = ({ appRoot }) => {
    const navigate = useNavigate();
    const push_mycart = () => navigate(Url.MYCART);

    const [isDropdownOpen, setDropdownOpen] = useState(false);
    const [isSideMenu, setSideMenu] = useState(false);
    const { isLogined, name } = appRoot.auth;

    const renderLoginPc = (): JSX.Element => {
        return (
            <div className="" id="navbarSupportedContent">
                <ul className="navbar-nav ml-auto">
                    {(() => {
                        if (isLogined) {
                            // ログイン済みの場合
                            return (
                                <>
                                    <Dropdown
                                        isOpen={isDropdownOpen}
                                        toggle={() => {
                                            setDropdownOpen(!isDropdownOpen);
                                        }}
                                    >
                                        <DropdownToggle
                                            caret
                                            nav
                                            id="logout-nav"
                                        >
                                            {name + " 様"}
                                        </DropdownToggle>
                                        <DropdownMenu>
                                            <DropdownItem
                                                onClick={(e) => {
                                                    e.preventDefault();
                                                    const element: HTMLFormElement =
                                                        document.getElementById(
                                                            "logout-form"
                                                        ) as HTMLFormElement;
                                                    if (element) {
                                                        element.submit();
                                                    }
                                                }}
                                            >
                                                ログアウト
                                            </DropdownItem>
                                            <Form
                                                id="logout-form"
                                                action={Url.LOGOUT}
                                                method="POST"
                                                style={{ display: "none" }}
                                            >
                                                <CSRFToken appRoot={appRoot} />
                                            </Form>
                                            <DropdownItem
                                                onClick={mycartSubmit}
                                            >
                                                カートを見る
                                            </DropdownItem>
                                        </DropdownMenu>
                                        <Form
                                            id="mycart-form"
                                            action={Url.MYCART}
                                            method="POST"
                                            style={{ display: "none" }}
                                        >
                                            <CSRFToken appRoot={appRoot} />
                                        </Form>
                                    </Dropdown>

                                    <a href="#" onClick={mycartSubmit}>
                                        <img
                                            src="/assets/front/image/cart.png"
                                            className="cartImg ml-3"
                                        />
                                    </a>
                                </>
                            );
                        } else {
                            // 未ログインの場合
                            return (
                                <>
                                    <li className="nav-item">
                                        <Link
                                            className="btn btn-danger mr-3"
                                            to={Url.LOGIN}
                                        >
                                            ログイン
                                        </Link>
                                    </li>
                                    <li className="nav-item">
                                        <Link
                                            className="btn btn-link text-danger"
                                            to={Url.REGISTER}
                                        >
                                            新規登録
                                        </Link>
                                    </li>
                                </>
                            );
                        }
                    })()}

                    <li className="nav-item">
                        <Link
                            className="btn btn-link text-danger"
                            to={Url.CONTACT}
                        >
                            お問い合わせ
                        </Link>
                    </li>
                </ul>
            </div>
        );
    };

    const renderLoginSp = (): JSX.Element => {
        const isSideMenuOpen = isSideMenu;
        const sideMenuClass = isSideMenuOpen ? "open" : "";
        const menuBtnClass = isSideMenuOpen ? "menu-btn on" : "menu-btn";
        const layerPanelClass = isSideMenuOpen ? "on" : "";

        return (
            <>
                <div
                    className={menuBtnClass}
                    onClick={(e) => {
                        e.preventDefault();
                        const isOpen = isSideMenu ? false : true;
                        setSideMenu(isOpen);
                    }}
                >
                    <figure></figure>
                    <figure></figure>
                    <figure></figure>
                </div>
                <div id="side-menu" className={sideMenuClass}>
                    <div className="side-menu-header">
                        <p style={{ margin: "20px", fontSize: "16px" }}>
                            {isLogined && name + " 様"}
                        </p>
                    </div>
                    <nav>
                        <ul>
                            {(() => {
                                if (isLogined) {
                                    // ログイン済みの場合
                                    return (
                                        <>
                                            <li>
                                                <a
                                                    href="#"
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        const element: HTMLFormElement =
                                                            document.getElementById(
                                                                "logout-form"
                                                            ) as HTMLFormElement;
                                                        if (element) {
                                                            element.submit();
                                                        }
                                                    }}
                                                >
                                                    ログアウト
                                                </a>
                                                <Form
                                                    id="logout-form"
                                                    action={Url.LOGOUT}
                                                    method="POST"
                                                    style={{ display: "none" }}
                                                >
                                                    <CSRFToken
                                                        appRoot={appRoot}
                                                    />
                                                </Form>
                                            </li>
                                            <li>
                                                <a
                                                    href="#"
                                                    onClick={mycartSubmit}
                                                >
                                                    カートを見る
                                                </a>
                                                <Form
                                                    id="mycart-form"
                                                    action={Url.MYCART}
                                                    method="POST"
                                                    style={{ display: "none" }}
                                                >
                                                    <CSRFToken
                                                        appRoot={appRoot}
                                                    />
                                                </Form>
                                            </li>
                                        </>
                                    );
                                } else {
                                    // 未ログインの場合
                                    return (
                                        <>
                                            <li>
                                                <Link
                                                    to={Url.LOGIN}
                                                    onClick={() => {
                                                        setSideMenu(false);
                                                    }}
                                                >
                                                    ログイン
                                                </Link>
                                            </li>
                                            <li>
                                                <Link
                                                    to={Url.REGISTER}
                                                    onClick={() => {
                                                        setSideMenu(false);
                                                    }}
                                                >
                                                    新規登録
                                                </Link>
                                            </li>
                                        </>
                                    );
                                }
                            })()}
                            <li>
                                <Link
                                    to={Url.CONTACT}
                                    onClick={() => {
                                        setSideMenu(false);
                                    }}
                                >
                                    お問い合わせ
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div id="layer-panel" className={layerPanelClass}></div>
            </>
        );
    };

    const mycartSubmit = (e) => {
        e.preventDefault();
        setSideMenu(false);
        push_mycart();
    };

    return (
        <>
            <header className="header shadow-sm">
                <nav className="navbar navbar-expand-md navbar-light bg-white headerNav">
                    <Logo />
                    {renderLoginPc()}
                    {renderLoginSp()}
                </nav>
            </header>
        </>
    );
};

export default CommonHeader;
