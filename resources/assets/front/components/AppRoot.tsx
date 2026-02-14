import { AppProvider } from '@/states/AppContext';
import { JSX, StrictMode } from 'react';
import { BrowserRouter } from 'react-router-dom';

const AppRoot = ({ children }: { children: JSX.Element }) => {
  return (
    <StrictMode>
      <AppProvider>
        <BrowserRouter>{children}</BrowserRouter>
      </AppProvider>
    </StrictMode>
  );
};

export default AppRoot;
