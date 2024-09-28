import React from 'react'
import { render } from '@testing-library/react'
import AdminTemplate, { AdminTemplateProps } from './index'
import MainService from '@/services/main'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const main = new MainService(() => ({}))
    const props: AdminTemplateProps = { main, title: 'サンプル' }
    const { asFragment } = render(<AdminTemplate {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
