import React, { FC } from 'react'
import { ContainerProps } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'
import LineButton from '@/components/01_atoms/LineButton'
import ScrollTop from '@/components/01_atoms/ScrollTop'
import Image from '@/components/01_atoms/Image'
import { useI18n } from '@/components/i18n'
import { type MenuItem } from '@/constants/menu'

/** FooterProps Props */
export type FooterProps = { menuItems: MenuItem[] }
/** Presenter Props */
export type PresenterProps = FooterProps & {
  t
}

/** Presenter Component */
const FooterPresenter: FC<PresenterProps> = ({
  t,
  menuItems = [],
  ...props
}) => (
  <>
    <footer className={`${styles.footer} relative`}>
      <div className="absolute left-0 right-0 -mt-64 md:-mt-32">
        <div className="flex w-full md:w-4/5 bg-main rounded-tr-full py-24 px-8 pb-16"></div>
        <div className="flex flex-wrap w-full md:w-4/5 bg-main md:px-40 pb-16">
          <div className="flex w-full md:w-1/2 justify-center md:justify-end items-center md:pr-8 mb-8">
            <Image
              src="https://qr-official.line.me/gs/M_618ecvqb_GW.png"
              alt="line-me"
            />
          </div>
          <div className="flex w-full md:w-1/2 justify-center md:justify-start items-center md:pl-8">
            <div className="px-8 md:px-0">
              <p className="text-accent text-xl md:text-3xl font-bold mb-6 ">
                {t(
                  'Why is the sky blue? Tell me a delicious recipe! and more!'
                )}
              </p>
              <LineButton
                link="#"
                label={t('Add me as a friend and ask me a question')}
              />
            </div>
          </div>
        </div>
      </div>
      <div className="bg-accent rounded-tl-full py-36 px-8 pb-16"></div>
      <div className="bg-accent py-96 px-8 pb-16">
        <nav className="flex flex-wrap items-center justify-center">
          {menuItems.map(({ label, href, target }, idx) => (
            <a
              href={href}
              target={target}
              rel={target ? 'noreferrer' : ''}
              className="w-full mb-6 md:w-48"
              key={idx}
            >
              <p className="text-white text-center font-bold">
                {'Twitter' === label ? (
                  <svg
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    className="text-white h-6 w-6 m-auto"
                  >
                    <path d="M8.12 20.329c7.75 0 11.99-6.414 11.99-11.989v-.546c.823-.597 1.534-1.336 2.097-2.184-.759.338-1.562.566-2.396.668a4.192 4.192 0 0 0 1.836-2.315 8.331 8.331 0 0 1-2.65 1.011 4.156 4.156 0 0 0-7.113 3.795 11.817 11.817 0 0 1-8.583-4.34 4.156 4.156 0 0 0 1.288 5.559A4.103 4.103 0 0 1 2.3 7.627v.055a4.156 4.156 0 0 0 3.32 4.072 4.16 4.16 0 0 1-1.868.071 4.155 4.155 0 0 0 3.87 2.878 8.343 8.343 0 0 1-5.164 1.78A8.756 8.756 0 0 1 0 16.736 11.722 11.722 0 0 0 8.12 20.33"></path>
                  </svg>
                ) : (
                  t(label)
                )}
              </p>
            </a>
          ))}
        </nav>
        <div className="flex items-center justify-center ">
          <p className="text-white text-center text-sm">
            © 2023 Ise Inc. All Right Reserved. Illustration by Yoshitaka Ise
          </p>
        </div>
      </div>
    </footer>
    <ScrollTop scrollY={2000} />
  </>
)

/** Container Component */
const FooterContainer: React.FC<
  ContainerProps<FooterProps, PresenterProps>
> = ({ presenter, ...props }) => {
  const { t } = useI18n('Common')

  return presenter({
    t,
    ...props,
  })
}

export default connect<FooterProps, PresenterProps>(
  'Footer',
  FooterPresenter,
  FooterContainer
)
