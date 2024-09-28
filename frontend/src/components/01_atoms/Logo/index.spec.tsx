import React from 'react'
import { render } from '@testing-library/react'
import Logo, { LogoProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: LogoProps = {
      name: 'sample',
      link: '#',
    }
    const { asFragment } = render(<Logo {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
