import { AppProvider } from '@/states/AppContext';
import { JSX, StrictMode } from 'react';
import { BrowserRouter } from 'react-router-dom';

const AppRoot = ({ children }: { children: JSX.Element }) => {
  return (
    <StrictMode>
      <AppProvider>
        <BrowserRouter future={{ v7_startTransition: true, v7_relativeSplatPath: true }}>
          {children}
        </BrowserRouter>
      </AppProvider>
    </StrictMode>
  );
};

export default AppRoot;
