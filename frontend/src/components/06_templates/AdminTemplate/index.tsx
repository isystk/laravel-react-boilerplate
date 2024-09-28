import Circles from '@/components/02_interactions/Circles'
import React, { FC, useState } from 'react'
import { ContainerProps, WithChildren } from 'types'
import { connect } from '@/components/hoc'
import HtmlSkeleton, {
  NoIndex,
  Title,
} from '@/components/05_layouts/HtmlSkeleton'
import MainService from '@/services/main'
import { adminMenuItems } from '@/constants/menu'
import Logo from '@/components/01_atoms/Logo'
import { Url } from '@/constants/url'
import { useI18n } from '@/components/i18n'
import { useRouter } from 'next/router'
import Breadcrumb, {
  type BreadcrumbItem,
} from '@/components/01_atoms/Breadcrumb'
import DropDown from '@/components/01_atoms/DropDown'
import Hamburger from '@/components/01_atoms/Hamburger'
import SideMenu from '@/components/04_organisms/SideMenu'

/** AdminTemplateProps Props */
export type AdminTemplateProps = WithChildren & {
  main: MainService
  title: string
  breadcrumb?: BreadcrumbItem[]
}
/** Presenter Props */
export type PresenterProps = AdminTemplateProps & {
  t
  logout
  router
  isMenuOpen
  setMenuOpen
}

/** Presenter Component */
const AdminTemplatePresenter: FC<PresenterProps> = ({
  children,
  main,
  t,
  title,
  breadcrumb = [],
  logout,
  router,
  isMenuOpen,
  setMenuOpen,
  ...props
}) => (
  <HtmlSkeleton>
    <Title>{`${title}－Admin`}</Title>
    <NoIndex />
    <div className="h-screen">
      <div className="h-16 bg-base p-4 md:p-0 flex">
        <div className="flex pr-3 z-50 md:hidden">
          <Hamburger isMenuOpen={isMenuOpen} setMenuOpen={setMenuOpen} />
        </div>
        <Logo link={Url.AdminHome} />
        {main.user && (
          <div className="ml-auto p-3">
            <DropDown
              label={main.user.userName}
              items={[{ label: 'ログアウト', link: () => logout() }]}
            />
          </div>
        )}
      </div>
      <div className="flex">
        <SideMenu
          isMenuOpen={isMenuOpen}
          setMenuOpen={setMenuOpen}
          menuItems={adminMenuItems}
        />
        <div className="w-full">
          <div
            className="flex items-center justify-center"
            style={{ height: 'calc(100vh - 4rem)' }}
          >
            <Circles>
              {main.user && <Breadcrumb items={breadcrumb} />}
              <div className="py-8 md:p-8 m-auto">{children}</div>
            </Circles>
          </div>
        </div>
      </div>
    </div>
  </HtmlSkeleton>
)

/** Container Component */
const AdminTemplateContainer: React.FC<
  ContainerProps<AdminTemplateProps, PresenterProps>
> = ({ presenter, main, children, ...props }) => {
  const router = useRouter()
  const { t } = useI18n('Common')
  const [isMenuOpen, setMenuOpen] = useState(false)

  const logout = async () => {
    await main.logout()
    location.reload()
  }

  return presenter({
    children,
    main,
    t,
    logout,
    router,
    isMenuOpen,
    setMenuOpen,
    ...props,
  })
}

export default connect<AdminTemplateProps, PresenterProps>(
  'AdminTemplate',
  AdminTemplatePresenter,
  AdminTemplateContainer
)
