import * as React from 'react'
import { connect } from 'react-redux'
import CommonHeader from './Commons/Header'
import CommonFooter from './Commons/Footer'

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

const mapStateToProps = (state: { parts: any; auth: any }) => {
  return {
    parts: state.parts,
    auth: state.auth,
  }
}

const mapDispatchToProps = {}

export default connect(mapStateToProps, mapDispatchToProps)(Layout)
