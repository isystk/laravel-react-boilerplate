import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { type User } from '@/states/auth';
import { AppProvider } from '@/states/AppContext';
import '@/assets/styles/app.scss';
import { StrictMode, Suspense } from 'react';

const render = (user: User) => {
  const container = document.getElementById('react-root');
  if (!container) {
    return;
  }

  const root = createRoot(container);
  root.render(
    <StrictMode>
      <Suspense fallback={<p>Loading...</p>}>
        <AppProvider>
          <Router user={user} />
        </AppProvider>
      </Suspense>
    </StrictMode>,
  );
};

const init = async () => {
  const params = new URLSearchParams();
  const url = '/api/session';
  try {
    const { data: user } = await axios.post(url, params);
    render(user);
  } catch (e) {
    render({} as User);
  }
};

// start
init();
