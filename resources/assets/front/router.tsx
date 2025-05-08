import { useEffect } from 'react';
import { Route, Routes } from 'react-router-dom';
import AuthCheck from '@/components/interactions/AuthCheck';
import ContactComplete from '@/pages/contact/complete';
import ContactCreate from '@/pages/contact';
import EMailForm from '@/pages/password/reset';
import Home from '@/pages/home';
import LoginForm from '@/pages/login';
import MyCart from '@/pages/mycart';
import ErrorPage from '@/components/organisms/ErrorPage';
import RegisterForm from '@/pages/register';
import ResetForm from '@/pages/password/reset/[id]';
import ShopComplete from '@/pages/complete';
import Top from '@/pages';
import Verify from '@/pages/email/verify';
import { Url } from '@/constants/url';
import useAppRoot from '@/states/useAppRoot';
import { type User } from '@/states/auth';

type Props = {
  user: User;
};

const Router = ({ user }: Props) => {
  const { state, service } = useAppRoot();

  useEffect(() => {
    (async () => {
      // セッションのセット
      await service.auth.setUser(user);
      // 定数のセット
      await service.const.readConsts();
    })();
  }, [service, user]);

  if (!state) return <></>;

  return (
    <Routes>
      <Route index element={<Top />} />
      <Route path={Url.LOGIN} element={<LoginForm />} />
      <Route path={Url.REGISTER} element={<RegisterForm />} />
      <Route path={Url.PASSWORD_RESET} element={<EMailForm />} />
      <Route path={`${Url.PASSWORD_RESET}/:token`} element={<ResetForm />} />
      <Route path={Url.EMAIL_VERIFY} element={<Verify />} />
      <Route path={Url.CONTACT} element={<ContactCreate />} />
      <Route path={Url.CONTACT_COMPLETE} element={<ContactComplete />} />

      {/* ★ログインユーザー専用ここから */}
      <Route path={Url.HOME} element={<AuthCheck user={user} component={<Home />} />} />
      <Route path={Url.MYCART} element={<AuthCheck user={user} component={<MyCart />} />} />
      <Route
        path={Url.PAY_COMPLETE}
        element={<AuthCheck user={user} component={<ShopComplete />} />}
      />
      {/* ★ログインユーザー専用ここまで */}

      <Route path="*" element={<ErrorPage status={404} />} />
    </Routes>
  );
};

export default Router;
