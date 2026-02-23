import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { type User } from '@/states/auth';
import AppRoot from '@/components/AppRoot';
import '@/assets/styles/app.scss';
import '@/i18n';
import { Api } from '@/constants/api';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const render = (user: User) => {
  const container = document.getElementById('react-root');
  if (!container) {
    return;
  }

  const root = createRoot(container);
  root.render(
    <AppRoot>
      <Router user={user} />
    </AppRoot>,
  );
};

const init = async () => {
  // Sanctum CSRF クッキーを取得
  await axios.get('/sanctum/csrf-cookie').catch(() => {});

  try {
    const { data: user } = await axios.get(Api.LOGIN_CHECK);
    render(user);
  } catch (e) {
    render({} as User);
  }
};

// start
init();
