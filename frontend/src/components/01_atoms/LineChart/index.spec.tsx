import React from 'react'
import { render } from '@testing-library/react'
import LineChart, { LineChartProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: LineChartProps = {
      data: [65, 59, 60, 81, 56, 55],
      labels: ['1 月', '2 月', '3 月', '4 月', '5 月', '6 月'],
      title: 'Dataset 1',
      color: 'rgb(255, 99, 132)',
    }
    const { asFragment } = render(<LineChart {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
