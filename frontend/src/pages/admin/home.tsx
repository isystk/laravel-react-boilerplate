import React, { FC } from 'react'
import AdminTemplate, {
  type AdminTemplateProps,
} from '@/components/06_templates/AdminTemplate'
import useAppRoot from '@/stores/useAppRoot'
import { withAuth } from '@/components/auth'
import { useI18n } from '@/components/i18n'
import useSWR from 'swr'
import { Api } from '@/constants/api'
import axios from '@/utils/axios'
import * as _ from 'lodash'
import LineChart, { LineChartProps } from '@/components/01_atoms/LineChart'

const Index: FC = () => {
  const main = useAppRoot()
  const { t } = useI18n('Common')

  const productId = 'prod_NpvV9ohJIlgElI'

  const {
    data: months,
    error,
    isLoading,
  } = useSWR(
    [Api.AdminSubscriberTrend, productId],
    async ([url, productId]) => {
      const result = await axios.post(url, {
        productId,
      })
      if (0 === result.data.length) {
        return undefined
      }
      return result.data
    }
  )

  if (isLoading) {
    // loading
    return <></>
  }

  const lineChartProps: LineChartProps = {
    data: _.map(months, 'subscriptions').map((e) => e.length),
    labels: _.map(months, 'month').map((e) => {
      const year = new Date(e).getFullYear()
      const month = new Date(e).getMonth() + 1
      return `${year}年${month}月`
    }),
    title: 'ChatGPTSlackBot',
    color: 'rgb(59, 130, 246)',
  }

  const props: AdminTemplateProps = { main, title: 'HOME' }
  return (
    <AdminTemplate {...props}>
      <section className="bg-white p-6 md:p-12 shadow-md">
        <h2 className="text-2xl mb-8 md:mb-10">契約者の推移</h2>
        <div className="flex flex-wrap items-center p-8 md:mb-16">
          <div className="flex w-full md:w-1/2 justify-center items-center">
            <LineChart {...lineChartProps} />
          </div>
        </div>
      </section>
    </AdminTemplate>
  )
}

export default withAuth(Index)
