import { ReactNode, useEffect } from 'react';
import Header from '@/components/organisms/Header';
import Footer from '@/components/organisms/Footer';
import Circles from '@/components/interactions/Circles';
import Loading from '@/components/atoms/Loading';
import FlashMessage from '@/components/atoms/FlashMessage';

type Props = {
  children: ReactNode;
  title: string;
};

const BasicLayout = ({ children, title }: Readonly<Props>) => {
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
    <>
      <Header />
      <Circles>
        <main className="content">{children}</main>
      </Circles>
      <Footer />
      <FlashMessage />
      <Loading />
    </>
  );
};

export default BasicLayout;
