import React, { FC, useState, useEffect } from 'react'
import AdminTemplate, {
  type AdminTemplateProps,
} from '@/components/06_templates/AdminTemplate'
import Input from '@/components/01_atoms/Input'
import Image from '@/components/01_atoms/Image'
import useAppRoot from '@/stores/useAppRoot'
import { withAuth } from '@/components/auth'
import { useI18n } from '@/components/i18n'
import useSWR from 'swr'
import { useRouter } from 'next/router'
import { Api } from '@/constants/api'
import { Url } from '@/constants/url'
import axios from '@/utils/axios'
import Table, { TableProps } from '@/components/01_atoms/Table'
import { TableColumn } from 'react-data-table-component'

const Index: FC = () => {
  const main = useAppRoot()
  const { t } = useI18n('Common')
  const {
    push,
    query: { productName },
  } = useRouter()
  const [fProductName, setFProductName] = useState('')

  useEffect(() => {
    setFProductName(Array.isArray(productName) ? productName[0] : productName)
  }, [productName])

  const {
    data: products,
    error,
    isLoading,
  } = useSWR([Api.Product], async ([url]) => {
    const result = await axios.get(url)
    if (0 === result.data.length) {
      return undefined
    }
    return result.data
  })

  if (isLoading) {
    // loading
    return <></>
  }

  const columns: TableColumn<Record<never, never>>[] = [
    {
      name: '商品名',
      sortable: true,
      selector: (row: { name: string }) => row.name,
    },
    {
      name: '画像',
      sortable: true,
      hidden: true,
      cell: ({ images, name }) => (
        <div className="p-1">
          <Image src={images[0]} alt={name} className="w-8" />
        </div>
      ),
    },
    {
      name: '詳細',
      sortable: true,
      selector: (row: { description: string }) => row.description,
    },
    {
      name: '価格',
      sortable: true,
      selector: ({ plans }) => {
        return plans.map(({ id, amount, currency, interval }) => {
          const amountFmt = amount
            ? new Intl.NumberFormat('ja', {
                style: 'currency',
                currency,
              }).format(amount)
            : ''
          return `${
            interval === 'month'
              ? t('monthly amount')
              : interval === 'year'
              ? t('yearly amount')
              : 'その他'
          } ${amountFmt} `
        })
      },
    },
    {
      name: '',
      hidden: true,
      cell: ({ name }) => {
        return (
          <>
            <button
              className="bg-blue-500 text-white py-2 px-4 rounded"
              onClick={() => {
                push(`${Url.AdminSubscriber}?productName=${name}`)
              }}
            >
              契約者一覧
            </button>
          </>
        )
      },
    },
  ]
  const tableProps: TableProps = {
    columns,
    data: products.filter(({ name }) => {
      if (fProductName && name !== fProductName) {
        return false
      }
      return true
    }),
  }

  const props: AdminTemplateProps = {
    main,
    title: '商品一覧',
    breadcrumb: [{ label: '商品一覧' }],
  }
  return (
    <AdminTemplate {...props}>
      <section className="bg-white p-6 md:p-12 shadow-md">
        <h2 className="text-2xl mb-8 md:mb-10">商品一覧</h2>

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
          </div>
        </form>

        {/* 商品一覧 */}
        <div className="overflow-x-auto">
          <Table {...tableProps} />
        </div>
      </section>
    </AdminTemplate>
  )
}

export default withAuth(Index)
