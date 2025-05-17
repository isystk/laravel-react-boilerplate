import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { type User } from '@/states/auth';
import { AppProvider } from '@/states/AppContext';
import '@/assets/styles/app.scss';
import { JSX, StrictMode } from 'react';
import { BrowserRouter } from 'react-router-dom';

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
  const url = '/api/session';
  try {
    const { data } = await axios.post(url, params);
    const { user } = data;
    render(user);
  } catch (e) {
    render({} as User);
  }
};

// start
init();
