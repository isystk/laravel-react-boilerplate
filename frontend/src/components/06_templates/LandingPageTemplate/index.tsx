import React, { FC, useEffect, useState } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import HtmlSkeleton, { Title } from '@/components/05_layouts/HtmlSkeleton'
import Header from '@/components/04_organisms/Header'
import MainVisual from '@/components/04_organisms/MainVisual'
import Price from '@/components/04_organisms/Price'
import News from '@/components/04_organisms/News'
import Footer from '@/components/04_organisms/Footer'
import SlideMenu from '@/components/04_organisms/SlideMenu'
import { frontMenuItems, frontFooterMenuItems } from '@/constants/menu'
import { APP_NAME, APP_URL } from '@/constants/index'

/** LandingPageTemplateProps Props */
export type LandingPageTemplateProps = WithChildren
/** Presenter Props */
export type PresenterProps = LandingPageTemplateProps & {
  productName: string
}

/** Presenter Component */
const LandingPageTemplatePresenter: FC<PresenterProps> = ({
  isMenuOpen,
  setMenuOpen,
  productName,
  jsonLd,
  ...props
}) => (
  <HtmlSkeleton>
    <Title>{productName}</Title>
    <Header
      isMenuOpen={isMenuOpen}
      setMenuOpen={setMenuOpen}
      menuItems={frontMenuItems}
      scrollY={700}
    />
    <MainVisual />
    <Price />
    <div className="h-96"></div>
    <News />
    <Footer menuItems={frontFooterMenuItems} />
    <div className="md:hidden">
      <SlideMenu
        isMenuOpen={isMenuOpen}
        setMenuOpen={setMenuOpen}
        menuItems={frontMenuItems}
      />
    </div>
    {/* SEO対策 */}
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
    />
  </HtmlSkeleton>
)

/** Container Component */
const LandingPageTemplateContainer: React.FC<
  ContainerProps<LandingPageTemplateProps, PresenterProps>
> = ({ presenter, ...props }) => {
  const [isMenuOpen, setMenuOpen] = useState(false)

  useEffect(() => {
    document.body.classList.add('bg-image')
  }, [])

  const productName = 'サンプル商品'

  const jsonLd = {
    '@context': 'https://schema.org/',
    '@type': 'Article',
    headline: APP_NAME,
    image: [`${APP_URL}/images/logo.png`],
    datePublished: '2023-05-20T00:00:00Z',
    author: {
      '@type': 'Person',
      name: 'Yoshitaka Ise',
      url: 'https://profile.isystk.com',
    },
    publisher: {
      '@type': 'Organization',
      name: productName,
      logo: {
        '@type': 'ImageObject',
        url: `${APP_URL}/images/iphone.png`,
        width: 430,
        height: 861,
      },
    },
    description: 'ここに商品の説明を記載します。',
  }

  return presenter({
    isMenuOpen,
    setMenuOpen,
    productName,
    jsonLd,
    ...props,
  })
}
export default connect<LandingPageTemplateProps, PresenterProps>(
  'LandingPageTemplate',
  LandingPageTemplatePresenter,
  LandingPageTemplateContainer
)
