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
import Avatar from '@/components/atoms/Avatar';

const Header = () => {
  const { state } = useAppRoot();
  const navigate = useNavigate();

  if (!state) return <></>;
  const { isLogined, name, avatar_url } = state.auth;

  const userLabel = (
    <div className="flex items-center gap-2">
      <Avatar src={avatar_url} size={24} />
      <span>{name} 様</span>
    </div>
  );

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
                      label={userLabel}
                      items={[
                        {
                          text: 'カートを見る',
                          onClick: () => navigate(Url.MYCART),
                        },
                        {
                          text: 'お問い合わせ',
                          onClick: () => navigate(Url.CONTACT),
                        },
                        {
                          text: 'プロフィール変更',
                          onClick: () => navigate(Url.PROFILE),
                        },
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
                      ]}
                    />
                  </li>
                );
              } else {
                return (
                  <>
                    <li>
                      <Link className="btn btn-danger" to={Url.LOGIN}>
                        ログイン
                      </Link>
                    </li>
                    <li>
                      <Link to={Url.REGISTER}>新規登録</Link>
                    </li>
                  </>
                );
              }
            })()}
            <li>
              <Link to={Url.MYCART}>
                <Image src={cartImage as string} width={30} height={30} alt="カート" />
              </Link>
            </li>
          </ul>
        </div>
        {/* サイドメニュー */}
        <SideMenu
          label={isLogined ? userLabel : ''}
          items={(() => {
            const items: Array<{ text: string; onClick?: () => void }> = [];
            items.push(
              {
                text: 'カートを見る',
                onClick: () => navigate(Url.MYCART),
              },
              {
                text: 'お問い合わせ',
                onClick: () => navigate(Url.CONTACT),
              },
            );
            if (isLogined) {
              items.push(
                {
                  text: 'プロフィール変更',
                  onClick: () => navigate(Url.PROFILE),
                },
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
              );
            } else {
              items.push(
                {
                  text: 'ログイン',
                  onClick: () => navigate(Url.LOGIN),
                },
                {
                  text: '新規登録',
                  onClick: () => navigate(Url.REGISTER),
                },
              );
            }
            return items;
          })()}
        />
      </nav>
      <form id="logout-form" action={Url.LOGOUT} method="POST">
        <CSRFToken />
      </form>
    </header>
  );
};

export default Header;
