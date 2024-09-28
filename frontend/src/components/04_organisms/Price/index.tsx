import React, { FC } from 'react'
import { ContainerProps } from 'types'
import * as styles from './styles'
import { connect } from '@/components/hoc'
import LineButton from '@/components/01_atoms/LineButton'
import ScrollIn from '@/components/02_interactions/ScrollIn'
import { useI18n } from '@/components/i18n'

/** PriceProps Props */
export type PriceProps = Record<never, never>
/** Presenter Props */
export type PresenterProps = PriceProps & {
  t
}

/** Presenter Component */
const PricePresenter: FC<PresenterProps> = ({ t, ...props }) => (
  <>
    <section className={`${styles.price}`}>
      <div className="flex items-center bg-black justify-center w-full">
        <div className="py-32 w-full">
          <ScrollIn>
            <p className="text-white font-bold text-3xl md:mb-16 text-center">
              PRICE
            </p>
          </ScrollIn>
          <div className="flex flex-wrap items-center p-8 md:mb-16">
            <div className="flex w-full md:w-1/2 justify-end items-center">
              <ScrollIn className="w-full md:w-72 md:mr-8">
                <div className="bg-white bg-opacity-20 rounded-lg py-16 h-64 mb-8 md:mb-0">
                  <p className="text-white text-center mb-8">
                    {t('Limited number of chats')}
                  </p>
                  <p className="text-white font-bold text-center text-5xl">
                    {t('free')}
                  </p>
                </div>
              </ScrollIn>
            </div>
            <div className="flex w-full md:w-1/2 justify-start items-center">
              <ScrollIn className="w-full md:w-72 md:ml-8">
                <div className="bg-white bg-opacity-20 rounded-lg py-16 h-64 ">
                  <p className="text-white text-center mb-8">
                    {t('Unlimited Chat')}
                  </p>
                  <div className="px-8">
                    <p className="text-white font-bold text-left text-4xl">
                      <span className="text-white text-xl mr-4">
                        {t('monthly amount')}
                      </span>
                      ¥980
                    </p>
                    <p className="text-white font-bold text-left text-4xl">
                      <span className="text-white text-xl mr-4">
                        {t('yearly amount')}
                      </span>
                      ¥9,800
                    </p>
                  </div>
                </div>
              </ScrollIn>
            </div>
          </div>
          <div className="flex items-center justify-center px-8">
            <LineButton
              link="#"
              label={t('Add me as a friend and ask me a question')}
            />
          </div>
        </div>
      </div>
    </section>
  </>
)

/** Container Component */
const PriceContainer: React.FC<ContainerProps<PriceProps, PresenterProps>> = ({
  presenter,
  ...props
}) => {
  const { t } = useI18n('Common')

  return presenter({
    t,
    ...props,
  })
}

export default connect<PriceProps, PresenterProps>(
  'Price',
  PricePresenter,
  PriceContainer
)
