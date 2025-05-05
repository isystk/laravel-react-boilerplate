import styles from './styles.module.scss';
import Logo from '@/components/atoms/Logo';
import { Link, useNavigate } from 'react-router-dom';
import { Url } from '@/constants/url';
import Image from '@/components/atoms/Image';
import DropDown from '@/components/atoms/DropDown';
import SideMenu from '@/components/organisms/SideMenu';
import useAppRoot from '@/states/useAppRoot';
import CSRFToken from '@/components/atoms/CSRFToken';
import cartImage from '@/assets/images/cart.png';

const Header = () => {
  const { state } = useAppRoot();
  if (!state) return <></>;

  const { isLogined, name } = state.auth;

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
                          text: 'ログアウト',
                          onClick: () => {
                            const element: HTMLFormElement = document.getElementById(
                              'logout-form',
                            ) as HTMLFormElement;
                            if (element) {
                              element.submit();
                            }
                          },
                        },
                        {
                          text: 'カートを見る',
                          onClick: () => navigate(Url.myCart),
                        },
                      ]}
                    />
                  </li>
                );
              } else {
                return (
                  <>
                    <li>
                      <Link className="btn btn-danger" to={Url.login}>
                        ログイン
                      </Link>
                    </li>
                    <li>
                      <Link to={Url.register}>新規登録</Link>
                    </li>
                  </>
                );
              }
            })()}
            <li>
              <Link to={Url.myCart}>
                <Image src={cartImage as string} width={30} height={30} alt="カート" />
              </Link>
            </li>
            <li>
              <Link to={Url.contact}>お問い合わせ</Link>
            </li>
          </ul>
        </div>
        {/* サイドメニュー */}
        <SideMenu
          text={isLogined ? `${name} 様` : ''}
          items={(() => {
            const items: Array<{ text: string; onClick?: () => void }> = [];
            if (isLogined) {
              items.push({
                text: 'ログアウト',
                onClick: () => {
                  const element: HTMLFormElement = document.getElementById(
                    'logout-form',
                  ) as HTMLFormElement;
                  if (element) {
                    element.submit();
                  }
                },
              });
            } else {
              items.push(
                {
                  text: 'ログイン',
                  onClick: () => navigate(Url.login),
                },
                {
                  text: '新規登録',
                  onClick: () => navigate(Url.register),
                },
              );
            }
            items.push(
              {
                text: 'カートを見る',
                onClick: () => navigate(Url.myCart),
              },
              {
                text: 'お問い合わせ',
                onClick: () => navigate(Url.contact),
              },
            );
            return items;
          })()}
        />
      </nav>
      <form id="logout-form" action={Url.logout} method="POST">
        <CSRFToken />
      </form>
    </header>
  );
};

export default Header;
