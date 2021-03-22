import * as React from 'react'
import { connect } from 'react-redux'
import CommonHeader from './common/common_header'
import CommonFooter from './common/common_footer'

interface IProps {
  children: any
}

class Layout extends React.Component<IProps> {
  constructor(props) {
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

const mapStateToProps = (state, ownProps) => {
  return {
    parts: state.parts,
    auth: state.auth,
  }
}

const mapDispatchToProps = {  }

export default connect(mapStateToProps, mapDispatchToProps)(Layout)
