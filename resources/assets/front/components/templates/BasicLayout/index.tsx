import { ReactNode, useEffect } from 'react';
import Header from '@/components/organisms/Header';
import Footer from '@/components/organisms/Footer';
import Circles from '@/components/interactions/Circles';
import Loading from '@/components/atoms/Loading';
import FlashMessage from '@/components/interactions/FlashMessage';
import { ToastMessage } from '@/components/interactions/ToastMessage';
import useAppRoot from '@/states/useAppRoot';
import { ErrorBoundary } from '@/components/interactions/ErrorBoundary';
import ScrollTopButton from '@/components/interactions/ScrollTopButton';

type Props = {
  children: ReactNode;
  title: string;
};

const BasicLayout = ({ children, title }: Readonly<Props>) => {
  const [state, service] = useAppRoot();
  if (!state) return null;

  // TODO React19以降では、useDocumentMetadataが追加される見込みだがそれまでは手動で直接書き換える
  useEffect(() => {
    document.title = title;
    const metaDescription = document.querySelector("meta[name='description']");
    if (metaDescription) {
      metaDescription.setAttribute(
        'content',
        'Laravel ＆ React.js の学習用サンプルアプリケーションです。',
      );
    }
  }, []);

  return (
    <ErrorBoundary>
      <Header />
      <Circles>
        <main className="content">{children}</main>
      </Circles>
      <Footer />
      <FlashMessage />
      <ToastMessage
        isOpen={!!state.toastMessage}
        message={state.toastMessage || ''}
        onConfirm={() => {
          service.hideToastMessage();
        }}
        onCancel={() => {
          service.hideToastMessage();
        }}
      />
      <ScrollTopButton />
      <Loading />
    </ErrorBoundary>
  );
};

export default BasicLayout;
