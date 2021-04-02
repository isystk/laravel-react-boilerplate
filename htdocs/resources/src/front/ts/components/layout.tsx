import * as React from 'react'
import CommonHeader from './Commons/Header'
import CommonFooter from '../containers/Commons/Footer'

type Props = {
  children: any
}

class Layout extends React.Component<Props> {
  constructor(props: Props | Readonly<Props>) {
    super(props)
  }

  render(): JSX.Element {
    return (
      <React.Fragment>
        <CommonHeader />

        {this.props.children}

        <CommonFooter />
      </React.Fragment>
    )
  }
}

export default Layout
