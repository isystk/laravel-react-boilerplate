import { Meta, Story } from '@storybook/react'
import React from 'react'
import Table, { TableProps } from './index'
import { TableColumn } from 'react-data-table-component'
import Image from '@/components/01_atoms/Image'

export default {
  title: '01_atoms/Table',
  component: Table,
} as Meta

const Template: Story = () => {
  const products = [
    {
      id: 1,
      name: '商品A',
      image: '/images/dummy_480x320.png',
      description: '商品Aです。',
      amount: 111,
    },
    {
      id: 2,
      name: '商品B',
      image: '/images/dummy_480x320.png',
      description: '商品Bです。',
      amount: 222,
    },
    {
      id: 3,
      name: '商品C',
      image: '/images/dummy_480x320.png',
      description: '商品Cです。',
      amount: 333,
    },
    {
      id: 4,
      name: '商品D',
      image: '/images/dummy_480x320.png',
      description: '商品Dです。',
      amount: 444,
    },
    {
      id: 5,
      name: '商品E',
      image: '/images/dummy_480x320.png',
      description: '商品Eです。',
      amount: 555,
    },
    {
      id: 6,
      name: '商品F',
      image: '/images/dummy_480x320.png',
      description: '商品Fです。',
      amount: 666,
    },
    {
      id: 7,
      name: '商品G',
      image: '/images/dummy_480x320.png',
      description: '商品Gです。',
      amount: 777,
    },
    {
      id: 8,
      name: '商品H',
      image: '/images/dummy_480x320.png',
      description: '商品Hです。',
      amount: 888,
    },
    {
      id: 9,
      name: '商品I',
      image: '/images/dummy_480x320.png',
      description: '商品Iです。',
      amount: 999,
    },
  ]
  const columns: TableColumn<Record<never, never>>[] = [
    {
      name: '商品名',
      sortable: true,
      selector: (row: { name: string }) => row.name,
    },
    {
      name: '画像',
      sortable: true,
      cell: ({ image, name }) => (
        <div className="p-1">
          <Image src={image} alt={name} className="w-8" />
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
      selector: (row: { amount: number }) => row.amount,
    },
  ]
  const tableProps: TableProps = {
    columns,
    data: products,
  }
  return <Table {...tableProps} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
