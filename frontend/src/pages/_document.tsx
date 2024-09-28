import React, { FC } from 'react'
import Document, {
  Html,
  Head,
  Main,
  NextScript,
  DocumentContext,
} from 'next/document'

const MyDocument: FC = () => {
  return (
    <Html lang="ja">
      <Head></Head>
      <body>
        <Main />
        <NextScript />
      </body>
    </Html>
  )
}

MyDocument.getInitialProps = async (ctx: DocumentContext) => {
  const initialProps = await Document.getInitialProps(ctx)
  return { ...initialProps }
}

export default MyDocument
