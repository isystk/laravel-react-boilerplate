import CSRFToken from '@/components/atoms/CSRFToken';
import { Url } from '@/constants/url';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/states/useAppRoot';
import { Link } from 'react-router-dom';
import TextInput from '@/components/atoms/TextInput';
import { useTranslation } from 'react-i18next';

const LoginForm = () => {
  const { state } = useAppRoot();
  const { t } = useTranslation('auth');

  if (!state) return <></>;

  return (
    <BasicLayout title={t('login.title')}>
      <div className="bg-white p-6 rounded-md shadow-md">
        <div className="text-center mb-3">
          <form method="GET" action={Url.AUTH_GOOGLE}>
            <button type="submit" className="btn btn-danger">
              {t('login.googleLogin')}
            </button>
          </form>
        </div>
        <form method="POST" action={Url.LOGIN} id="login-form">
          <CSRFToken />
          <div className="mx-auto md:w-100">
            <TextInput
              identity="email"
              controlType="email"
              label={t('login.email')}
              autoFocus={true}
              className="mb-5 md:w-100"
            />
            <TextInput
              identity="password"
              controlType="password"
              autoComplete="current-password"
              label={t('login.password')}
              className="mb-5 md:w-100"
            />
            {/*TODO react-google-recaptcha-v3 が react19では未だサポートされていないのでコメントアウト*/}
            {/*<GoogleReCaptchaProvider*/}
            {/*    reCaptchaKey={*/}
            {/*        import.meta.env.MIX_RECAPTCHAV3_SITEKEY + ""*/}
            {/*    }*/}
            {/*    language="ja"*/}
            {/*>*/}
            {/*    <ReChaptcha />*/}
            {/*</GoogleReCaptchaProvider>*/}
            <p>
              <input type="checkbox" id="remember" name="remember" value="1" />{' '}
              <span>{t('login.rememberMe')}</span>
            </p>
          </div>
          <div className="mx-auto my-5 border p-3 md:w-100">
            {t('login.testUser')}
            <br />
            {t('login.testEmail')}
            <br />
            {t('login.testPassword')}
          </div>
          <div className="mt-3 text-center">
            <button type="submit" className="btn btn-primary mr-5">
              {t('login.submit')}
            </button>
            <Link to={Url.PASSWORD_RESET} className="btn">
              {t('login.forgotPassword')}
            </Link>
          </div>
        </form>
      </div>
    </BasicLayout>
  );
};

export default LoginForm;
