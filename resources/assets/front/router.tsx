import { useEffect } from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
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
import Top from '@/pages/top';
import Verify from '@/pages/email/verify';
import { Url } from '@/constants/url';
import useAppRoot from '@/states/useAppRoot';
import { Auth } from '@/states/auth';

type Props = {
  auth: Auth;
};

const Router = ({ auth }: Props) => {
  const { state, service } = useAppRoot();

  useEffect(() => {
    if (!state || !service) return;
    // セッションのセット
    service.auth.setAuth(auth);

    (async () => {
      // 定数のセット
      await service.const.readConsts();
    })();
  }, [state]);

  if (!state) return <></>;

  return (
    <BrowserRouter>
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
        <Route path={Url.home} element={<AuthCheck auth={auth} component={<Home />} />} />
        <Route path={Url.myCart} element={<AuthCheck auth={auth} component={<MyCart />} />} />
        <Route
          path={Url.payComplete}
          element={<AuthCheck auth={auth} component={<ShopComplete />} />}
        />
        {/* ★ログインユーザー専用ここまで */}

        <Route path="*" element={<ErrorPage status={404} />} />
      </Routes>
    </BrowserRouter>
  );
};

export default Router;
