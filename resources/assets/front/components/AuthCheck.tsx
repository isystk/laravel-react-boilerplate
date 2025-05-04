import { Navigate } from 'react-router-dom';
import { Url } from '@/constants/url';
import { Auth } from '@/states/auth';
import { ReactNode } from 'react';

type Props = {
  auth: Auth;
  component: ReactNode;
};

const AuthCheck = ({ auth, component }: Props) => {
  // ログインしてなければログイン画面へとばす
  if (!auth.id) {
    return <Navigate to={Url.login} />;
  }

  // 新規会員登録後、メール確認が未完了の場合
  if (!auth.email_verified_at) {
    return <Navigate to={Url.emailVerify} />;
  }

  // ログイン済みの場合
  return <>{component}</>;
};

export default AuthCheck;
