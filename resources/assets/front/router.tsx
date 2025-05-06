import { useEffect } from 'react';
import { Route, Routes } from 'react-router-dom';
import AuthCheck from '@/components/AuthCheck';
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
    if (!state) return;
    // セッションのセット
    service.auth.setUser(user);

    (async () => {
      // 定数のセット
      await service.const.readConsts();
    })();
  }, [state]);

  if (!state) return <></>;

  return (
    <Routes>
      <Route index element={<Top />} />
      <Route path={Url.login} element={<LoginForm />} />
      <Route path={Url.register} element={<RegisterForm />} />
      <Route path={Url.passwordReset} element={<EMailForm />} />
      <Route path={`${Url.passwordReset}/:token`} element={<ResetForm />} />
      <Route path={Url.emailVerify} element={<Verify />} />
      <Route path={Url.contact} element={<ContactCreate />} />
      <Route path={Url.contactComplete} element={<ContactComplete />} />

      {/* ★ログインユーザー専用ここから */}
      <Route path={Url.home} element={<AuthCheck user={user} component={<Home />} />} />
      <Route path={Url.myCart} element={<AuthCheck user={user} component={<MyCart />} />} />
      <Route
        path={Url.payComplete}
        element={<AuthCheck user={user} component={<ShopComplete />} />}
      />
      {/* ★ログインユーザー専用ここまで */}

      <Route path="*" element={<ErrorPage status={404} />} />
    </Routes>
  );
};

export default Router;
