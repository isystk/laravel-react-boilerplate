import React from 'react'
import { render } from '@testing-library/react'
import ErrorTemplate, { ErrorTemplateProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: ErrorTemplateProps = { statusCode: 404 }
    const { asFragment } = render(<ErrorTemplate {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
