import { Meta, Story } from '@storybook/react'
import React from 'react'
import LineChart, { LineChartProps } from './index'

export default {
  title: '01_atoms/LineChart',
  component: LineChart,
} as Meta

const Template: Story = () => {
  const props: LineChartProps = {
    data: [65, 59, 60, 81, 56, 55],
    labels: ['1 月', '2 月', '3 月', '4 月', '5 月', '6 月'],
    title: 'Dataset 1',
    color: 'rgb(255, 99, 132)',
  }
  return <LineChart {...props} />
}

export const Primary = Template.bind({})
Primary.storyName = 'プライマリ'
Primary.args = {}
