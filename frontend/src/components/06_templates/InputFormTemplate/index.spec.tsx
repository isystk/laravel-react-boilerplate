import React from 'react'
import { render } from '@testing-library/react'
import InputFormTemplate, { InputFormTemplateProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: InputFormTemplateProps = { title: 'サンプル' }
    const { asFragment } = render(<InputFormTemplate {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
