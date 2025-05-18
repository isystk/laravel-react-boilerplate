import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { type User } from '@/states/auth';
import { AppProvider } from '@/states/AppContext';
import '@/assets/styles/app.scss';
import { JSX, StrictMode } from 'react';
import { BrowserRouter } from 'react-router-dom';
import { Api } from '@/constants/api';

// --- アプリケーションのRootコンポーネント ---
export const AppRoot = ({ children }: { children: JSX.Element }) => {
  return (
    <StrictMode>
      <AppProvider>
        <BrowserRouter>{children}</BrowserRouter>
      </AppProvider>
    </StrictMode>
  );
};

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
  const params = new URLSearchParams();
  try {
    const { data: user } = await axios.post(Api.LOGIN_CHECK, params);
    render(user);
  } catch (e) {
    render({} as User);
  }
};

// start
init();
