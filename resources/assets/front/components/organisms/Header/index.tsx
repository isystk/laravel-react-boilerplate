import styles from './styles.module.scss';
import Logo from '@/components/atoms/Logo';
import { Link, useNavigate } from 'react-router-dom';
import { Url } from '@/constants/url';
import DropDown from '@/components/atoms/DropDown';
import SideMenu from '@/components/organisms/SideMenu';
import useAppRoot from '@/states/useAppRoot';
import Avatar from '@/components/atoms/Avatar';
import { useTranslation } from 'react-i18next';
import axios from 'axios';
import { Api } from '@/constants/api';

const Header = () => {
  const { state } = useAppRoot();
  const navigate = useNavigate();
  const { t } = useTranslation();

  if (!state) return <></>;
  const { isLogined, name, avatar_url } = state.auth;

  const userLabel = (
    <div className="flex items-center gap-2">
      <Avatar src={avatar_url} size={24} />
      <span>{t('honorific', { name })}</span>
    </div>
  );

  const handleLogout = async () => {
    try {
      await axios.post(Api.LOGOUT);
      window.location.href = Url.TOP;
    } catch (error) {
      console.error('Logout failed', error);
    }
  };

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
                          text: t('nav.myPage'),
                          onClick: () => navigate(Url.HOME),
                        },
                        {
                          text: t('nav.viewCart'),
                          onClick: () => navigate(Url.MYCART),
                        },
                        {
                          text: t('nav.contact'),
                          onClick: () => navigate(Url.CONTACT),
                        },
                        {
                          text: t('nav.editProfile'),
                          onClick: () => navigate(Url.PROFILE),
                        },
                        {
                          text: t('nav.logout'),
                          onClick: handleLogout,
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
                        {t('nav.login')}
                      </Link>
                    </li>
                    <li>
                      <Link to={Url.REGISTER}>{t('nav.register')}</Link>
                    </li>
                  </>
                );
              }
            })()}
          </ul>
        </div>
        {/* サイドメニュー */}
        <SideMenu
          label={isLogined ? userLabel : ''}
          items={(() => {
            const items: Array<{ text: string; onClick?: () => void }> = [];
            if (isLogined) {
              items.push(
                {
                  text: t('nav.myPage'),
                  onClick: () => navigate(Url.HOME),
                },
                {
                  text: t('nav.viewCart'),
                  onClick: () => navigate(Url.MYCART),
                },
                {
                  text: t('nav.contact'),
                  onClick: () => navigate(Url.CONTACT),
                },
                {
                  text: t('nav.editProfile'),
                  onClick: () => navigate(Url.PROFILE),
                },
                {
                  text: t('nav.logout'),
                  onClick: handleLogout,
                },
              );
            } else {
              items.push(
                {
                  text: t('nav.login'),
                  onClick: () => navigate(Url.LOGIN),
                },
                {
                  text: t('nav.register'),
                  onClick: () => navigate(Url.REGISTER),
                },
              );
            }
            return items;
          })()}
        />
      </nav>
    </header>
  );
};

export default Header;
