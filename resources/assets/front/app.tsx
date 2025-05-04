import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { Auth } from '@/states/auth';
import { AppProvider } from '@/states/AppContext';
import '@/assets/styles/app.scss';
import { StrictMode, Suspense } from 'react';

const render = (data: Auth) => {
  const container = document.getElementById('react-root');
  if (!container) {
    return;
  }

  const root = createRoot(container);
  root.render(
    <StrictMode>
      <Suspense fallback={<p>Loading...</p>}>
        <AppProvider>
          <Router auth={data} />
        </AppProvider>
      </Suspense>
    </StrictMode>,
  );
};

const init = async () => {
  const params = new URLSearchParams();
  const url = '/api/session';
  try {
    const { data } = await axios.post(url, params);
    render(data);
  } catch (e) {
    render({} as Auth);
  }
};

// start
init();
