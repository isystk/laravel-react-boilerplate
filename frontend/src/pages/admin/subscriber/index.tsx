import React, { FC, useState, useEffect } from 'react'
import AdminTemplate, {
  type AdminTemplateProps,
} from '@/components/06_templates/AdminTemplate'
import useAppRoot from '@/stores/useAppRoot'
import { withAuth } from '@/components/auth'
import { useI18n } from '@/components/i18n'
import useSWR from 'swr'
import { useRouter } from 'next/router'
import { Api } from '@/constants/api'
import axios from '@/utils/axios'
import { dateFormat, unixTimeToDate } from '@/utils/general'
import Table, { TableProps } from '@/components/01_atoms/Table'
import Input from '@/components/01_atoms/Input'
import DateInput from '@/components/01_atoms/DateInput'
import { TableColumn } from 'react-data-table-component'
import { AxiosError } from 'axios'
import Loading from '@/components/01_atoms/Loading'
import Modal from '@/components/02_interactions/Modal'
import { useForm } from 'react-hook-form'

type FormInputs = {
  dateStart: string
}

const Index: FC = () => {
  const main = useAppRoot()
  const { t } = useI18n('Common')
  const {
    query: { productName, customerName },
  } = useRouter()
  const [fProductName, setFProductName] = useState('')
  const [fCustomerName, setFCustomerName] = useState('')
  const [fPeriodFrom, setFPeriodFrom] = useState('')
  const [fPeriodTo, setFPeriodTo] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [subscriptionId, setSubscriptionId] = useState('')
  const [loading, setLoading] = useState(false)

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<FormInputs>()

  useEffect(() => {
    setFProductName(Array.isArray(productName) ? productName[0] : productName)
    setFCustomerName(
      Array.isArray(customerName) ? customerName[0] : customerName
    )
  }, [productName, customerName])

  const {
    data: customers,
    error,
    isLoading,
    mutate,
  } = useSWR(Api.AdminSubscriber, async (url) => {
    const result = await axios.post(url)
    if (0 === result.data.length) {
      return undefined
    }
    return result.data
  })

  if (isLoading) {
    // loading
    return <></>
  }

  const handleCancel = async () => {
    try {
      setLoading(true)

      // call the backend to create subscription
      const {
        data: { message, error },
      } = await axios.post(Api.AdminCancel, {
        subscriptionId,
      })

      // push(generateURL(Url.AdminSubscriber, {
      //   productName: fProductName,
      //   customerName: fCustomerName
      // }))
      // 一覧を最新化
      mutate()
    } catch (e: unknown) {
      console.log(e)
      if (e instanceof AxiosError) {
        const { response } = e
        alert(response?.data?.message)
      } else if (e instanceof Error) {
        alert(e.message)
      }
    } finally {
      setLoading(false)
      setShowModal(false)
    }
  }

  const columns: TableColumn<Record<never, never>>[] = [
    {
      name: '商品名',
      sortable: true,
      selector: (row: { productName: string }) => row.productName,
    },
    {
      name: '顧客名',
      sortable: true,
      selector: (row: { customerName: string }) => row.customerName,
    },
    {
      name: 'メールアドレス',
      sortable: true,
      selector: (row: { email: string }) => row.email,
    },
    {
      name: 'プラン',
      sortable: true,
      selector: (row: {
        currency: string
        amount: number
        interval: string
      }) => {
        const amountFmt = row.amount
          ? new Intl.NumberFormat('ja', {
              style: 'currency',
              currency: row.currency,
            }).format(row.amount)
          : ''
        const interval =
          row.interval === 'month'
            ? t('monthly amount')
            : row.interval === 'year'
            ? t('yearly amount')
            : 'その他'
        return `${interval} ${amountFmt}`
      },
    },
    {
      name: 'ステータス',
      sortable: true,
      selector: (row: { status: string }) => row.status,
    },
    {
      name: '契約開始日',
      sortable: true,
      selector: (row: { current_period_start: number }) =>
        row.current_period_start &&
        dateFormat(unixTimeToDate(row.current_period_start)),
    },
    {
      name: '解約予定日',
      sortable: true,
      selector: (row: { cancel_at: number }) =>
        row.cancel_at && dateFormat(unixTimeToDate(row.cancel_at)),
    },
    {
      name: '',
      hidden: true,
      cell: ({ id }) => {
        return (
          <>
            <button
              className="bg-blue-500 text-white py-2 px-4 rounded"
              onClick={() => {
                setSubscriptionId(id)
                setShowModal(true)
              }}
            >
              途中解約
            </button>
          </>
        )
      },
    },
  ]
  const tableProps: TableProps = {
    columns,
    data: customers.filter(
      ({
        productName,
        customerName,
        current_period_start,
        current_period_end,
      }) => {
        if (fProductName && productName !== fProductName) {
          return false
        }
        if (fCustomerName && customerName !== fCustomerName) {
          return false
        }
        if (fPeriodFrom) {
          const fPeriodFromDate = new Date(fPeriodFrom)
          fPeriodFromDate.setHours(0, 0, 0, 0)
          const currentPeriodEndDate = new Date(current_period_end * 1000)
          currentPeriodEndDate.setHours(0, 0, 0, 0)
          if (currentPeriodEndDate.getTime() < fPeriodFromDate.getTime()) {
            return false
          }
        }
        if (fPeriodTo) {
          const fPeriodToDate = new Date(fPeriodTo)
          fPeriodToDate.setHours(0, 0, 0, 0)
          const currentPeriodStartDate = new Date(current_period_start * 1000)
          currentPeriodStartDate.setHours(0, 0, 0, 0)
          if (fPeriodToDate.getTime() < currentPeriodStartDate.getTime()) {
            return false
          }
        }
        return true
      }
    ),
  }

  const props: AdminTemplateProps = {
    main,
    title: '契約一覧',
    breadcrumb: [{ label: '契約一覧' }],
  }
  return (
    <AdminTemplate {...props}>
      <section className="bg-white p-6 md:p-12 shadow-md">
        <h2 className="text-2xl mb-8 md:mb-10">契約一覧</h2>

        {/* 検索フォーム */}
        <form className="mb-6">
          <div className="flex flex-wrap -mx-2">
            <div className="w-full md:w-1/3 px-2 mb-4 md:mb-0">
              <label
                className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                htmlFor="product-name"
              >
                商品名
              </label>
              <Input
                {...{
                  small: true,
                  name: 'product-name',
                  type: 'text',
                  placeholder: '商品名',
                  value: fProductName,
                  onChange: (value) => {
                    setFProductName(value)
                  },
                }}
              />
            </div>
            <div className="w-full md:w-1/3 px-2 mb-4 md:mb-0">
              <label
                className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                htmlFor="product-name"
              >
                顧客名
              </label>
              <Input
                {...{
                  small: true,
                  name: 'customer-name',
                  type: 'text',
                  placeholder: '顧客名',
                  value: fCustomerName,
                  onChange: (value) => {
                    setFCustomerName(value)
                  },
                }}
              />
            </div>
          </div>
          <div className="flex flex-wrap -mx-2">
            <div className="w-full px-2 mb-4 md:mb-0">
              <label
                className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                htmlFor="product-name"
              >
                契約期間
              </label>
              <div className="flex flex-wrap w-full">
                <DateInput
                  {...{
                    small: true,
                    name: 'periodFrom',
                    placeholder: '契約期間（開始）',
                    value: fPeriodFrom,
                    onChange: (date) => {
                      setFPeriodFrom(date)
                    },
                  }}
                />
                <span className="p-3">～</span>
                <DateInput
                  {...{
                    small: true,
                    name: 'periodTo',
                    placeholder: '契約期間（終了）',
                    value: fPeriodTo,
                    onChange: (date) => {
                      setFPeriodTo(date)
                    },
                  }}
                />
              </div>
            </div>
          </div>
        </form>

        {/* 契約一覧 */}
        <div className="overflow-x-auto">
          <Table {...tableProps} />
        </div>
        <Loading loading={loading} />
      </section>
      <Modal
        isOpen={showModal}
        handleCancel={() => setShowModal(false)}
        handleAccept={() => handleCancel()}
        acceptLabel={t('Yes')}
        cancelLabel={t('No')}
      >
        <svg
          aria-hidden="true"
          className="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          ></path>
        </svg>
        <h3 className="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
          {t('I will cancel the contract. May I?')}
        </h3>
      </Modal>
    </AdminTemplate>
  )
}

export default withAuth(Index)
