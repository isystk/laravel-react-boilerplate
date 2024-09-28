import HtmlSkeleton, {
  HtmlSkeletonProps,
  NoIndex,
  Title,
} from '@/components/05_layouts/HtmlSkeleton'
import { connect } from '@/components/hoc'
import React, { useState } from 'react'
import { ContainerProps } from 'types'
import Header from '@/components/04_organisms/Header'
import SlideMenu from '@/components/04_organisms/SlideMenu'
import { frontMenuItems } from '@/constants/menu'
import { useI18n } from '@/components/i18n'

/** ErrorTemplate Props */
export type ErrorTemplateProps = Omit<HtmlSkeletonProps, 'children'> & {
  statusCode: number
}
/** Presenter Props */
export type PresenterProps = ErrorTemplateProps & {
  isMenuOpen: boolean
  setMenuOpen: () => void
  t
}

/** Presenter Component */
const ErrorTemplatePresenter: React.FC<PresenterProps> = ({
  statusCode,
  isMenuOpen,
  setMenuOpen,
  t,
  ...props
}) => (
  <HtmlSkeleton>
    <Title>Page Not Found</Title>
    <NoIndex />
    <Header
      isMenuOpen={isMenuOpen}
      setMenuOpen={setMenuOpen}
      menuItems={frontMenuItems}
    />
    <div className="flex items-center justify-center">
      <div className="p-3 md:p-32 w-full ">
        <div className="py-8">
          <p className="font-bold text-3xl md:mb-16 text-center">
            {statusCode} {t('Error')}
          </p>
          <div className="flex flex-wrap items-center p-8 md:mb-16">
            <div className="flex w-full justify-center items-center">
              <p className="text-2xl ">
                {t(
                  'The page could not be displayed properly. Please check the information you entered and try again in a few minutes.'
                )}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div className="md:hidden">
      <SlideMenu
        isMenuOpen={isMenuOpen}
        setMenuOpen={setMenuOpen}
        menuItems={frontMenuItems}
      />
    </div>
  </HtmlSkeleton>
)

/** Container Component */
const ErrorTemplateContainer: React.FC<
  ContainerProps<ErrorTemplateProps, PresenterProps>
> = ({ presenter, ...props }) => {
  const [isMenuOpen, setMenuOpen] = useState(false)
  const { t } = useI18n('Common')
  return presenter({
    isMenuOpen,
    setMenuOpen,
    t,
    ...props,
  })
}

/** ErrorTemplate */
export default connect<ErrorTemplateProps, PresenterProps>(
  'ErrorTemplate',
  ErrorTemplatePresenter,
  ErrorTemplateContainer
)
