import * as React from 'react'
import { connect } from 'react-redux'
import { Link } from 'react-router-dom'
import { URL } from '../common/constants/url'
import CommonHeader from './common/common_header'
import CommonFooter from './common/common_footer'
import { Auth } from '../store/StoreTypes'

import { authCheck, authLogout } from '../actions'

interface IProps {
  auth: Auth
  authCheck
  authLogout
}

class Layout extends React.Component<IProps> {
  constructor(props) {
    super(props)
    this.logoutClick = this.logoutClick.bind(this)
  }

  componentDidMount(): void {
    this.props.authCheck()
  }

  async logoutClick(): Promise<void> {
    await this.props.authLogout()
    location.reload()
  }

  logoutLink(): JSX.Element {
    const { auth } = this.props

    if (auth.isLogin) {
      return <a onClick={this.logoutClick}>ログアウト</a>
    }
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

const mapDispatchToProps = { authCheck, authLogout }

export default connect(mapStateToProps, mapDispatchToProps)(Layout)
