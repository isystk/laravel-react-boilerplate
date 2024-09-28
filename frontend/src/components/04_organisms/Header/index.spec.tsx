import React from 'react'
import { render } from '@testing-library/react'
import Header, { HeaderProps } from './index'

describe('LandingPageTemplate', () => {
  it('Match Snapshot', () => {
    const props: HeaderProps = {
      isMenuOpen: true,
      setMenuOpen: () => ({}),
      menuItems: [
        {
          label: 'Menu1',
          href: 'https://menu1',
        },
        {
          label: 'Menu2',
          href: 'https://menu2',
        },
        {
          label: 'Menu3',
          href: 'https://menu3',
          target: '_blank',
        },
      ],
    }
    const { asFragment } = render(<Header {...props} />)
    expect(asFragment()).toMatchSnapshot()
  })
})
