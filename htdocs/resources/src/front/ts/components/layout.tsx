import * as React from 'react'
import { connect } from 'react-redux'
import { Link } from 'react-router-dom'
import { URL } from '../common/constants/url'
import CommonHeader from './common/common_header'
import CommonFooter from './common/common_footer'
import { Auth } from '../store/StoreTypes'

interface IProps {
  auth: Auth
}

class Layout extends React.Component<IProps> {
  constructor(props) {
    super(props)
  }

  logoutLink(): JSX.Element {
    const { auth } = this.props

    // if (auth.isLogin) {
    //   return <a onClick={this.logoutClick}>ログアウト</a>
    // }
    return <Link to={URL.LOGIN}>ログイン</Link>
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
