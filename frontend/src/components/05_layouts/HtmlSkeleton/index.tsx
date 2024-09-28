import React, { Children, FC } from 'react'
import { connect } from '@/components/hoc'
import { ContainerProps, WithChildren } from 'types'
import Title, { TitleProps } from './Title'
import NoIndex, { NoIndexProps } from './NoIndex'
import Head from 'next/head'
import { isReactElement } from '@/utils/general/object'
import { APP_NAME, APP_DESCRIPTION } from '@/constants'

/** HtmlSkeleton Props */
export type HtmlSkeletonProps = WithChildren
/** Presenter Props */
export type PresenterProps = HtmlSkeletonProps & {
  title?: string
  description: string
}

/** Presenter Component */
const HtmlSkeletonPresenter: FC<PresenterProps> = ({
  title,
  noIndex,
  description,
  children,
}) => (
  <>
    <Head>
      {/* タイトル */}
      <title>{title}</title>
      {/* Index or Noindex */}
      <meta name="robots" content={noIndex ? 'noindex' : 'index'} />
      {/* favicon */}
      <link rel="icon" href="/favicon.ico" />
      {/* PCやモバイル（スマホ、タブレット）などのデバイスごとのコンテンツの表示領域 */}
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      {/* ブラウザテーマカラー */}
      <meta name="theme-color" content="#ffffff" />
      {/* サイト概要 */}
      <meta name="description" content={description} />
      {/* OGP 画像URL */}
      <meta property="og:image" content={'/ogp-image.png'} />
      {/* OGP タイトル */}
      <meta name="og:title" content={title} />
      {/* OGP サイト概要 */}
      <meta name="og:description" content={description} />
      {/* OGP Twitterカード */}
      <meta name="twitter:card" content="summary" />
      {/* apple ポータブル端末 アイコン */}
      <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
      {/* manifest.json */}
      <link rel="manifest" href="/manifest.json" />
    </Head>
    {children}
  </>
)

/** Container Component */
const HtmlSkeletonContainer: React.FC<
  ContainerProps<HtmlSkeletonProps, PresenterProps>
> = ({ presenter, children, ...props }) => {
  let title: TitleProps['children'] | undefined = undefined
  let noIndex = false

  children = Children.map(children, (child) => {
    if (isReactElement(child) && child.type === Title) {
      // Titleタグが含まれる場合
      return (title = `${child.props.children} | ${APP_NAME}`) && undefined
    }
    if (isReactElement(child) && child.type === NoIndex) {
      // NoIndexタグが含まれる場合
      return (noIndex = true) && undefined
    }
    return child
  })
  if (!title) title = APP_NAME

  const description = APP_DESCRIPTION
  return presenter({
    title,
    noIndex,
    description,
    children,
    ...props,
  })
}

export const Context = React.createContext()

export default connect<HtmlSkeletonProps, PresenterProps>(
  'HtmlSkeleton',
  HtmlSkeletonPresenter,
  HtmlSkeletonContainer
)

// Sub Component
export type { TitleProps }
export { Title }
export type { NoIndexProps }
export { NoIndex }
