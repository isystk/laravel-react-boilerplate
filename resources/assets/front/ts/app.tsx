import Router from '@/router';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import { Session } from '@/states/auth';
import { AppProvider } from '@/states/AppContext';
import './styles/app.scss';
import { StrictMode, Suspense } from 'react';

const render = (session: Session) => {
  const container = document.getElementById('react-root');
  if (!container) {
    return;
  }

  const root = createRoot(container);
  root.render(
    <StrictMode>
      <Suspense fallback={<p>Loading...</p>}>
        <AppProvider>
          <Router session={session} />
        </AppProvider>
      </Suspense>
    </StrictMode>,
  );
};

const init = async () => {
  const params = new URLSearchParams();
  const url = '/api/session';
  try {
    const { data: session } = await axios.post(url, params);
    render(session);
  } catch (e) {
    render({} as Session);
  }
};

// start
init();
