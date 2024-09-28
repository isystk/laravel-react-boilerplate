import React from 'react'
import { render } from '@testing-library/react'
import LandingPageTemplate from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const { asFragment } = render(<LandingPageTemplate />)
    expect(asFragment()).toMatchSnapshot()
  })
})
