import React from 'react'
import { render } from '@testing-library/react'
import Breadcrumb, { BreadcrumbProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: BreadcrumbProps = {
      items: [
        { label: 'リンク1', link: 'http://link1' },
        { label: 'リンク2', link: 'http://link2' },
        { label: 'リンク3' },
      ],
    }
    const { asFragment } = render(<Breadcrumb {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
