import * as React from 'react'
import { connect } from 'react-redux'
import { Auth } from '../../store/StoreTypes'
import { withRouter } from 'react-router-dom'

import { authCheck } from '../../actions'

interface IProps {
  auth: Auth
  authCheck
  history
}

interface IState {
  loaded: boolean
}

export class AuthCheck extends React.Component<IProps, IState> {
  constructor(props) {
    super(props)
    this.state = {
      loaded: false,
    }
  }

  UNSAFE_componentWillMount():void {
    this.checkAuth()
  }

  async checkAuth(): Promise<void> {
    await this.props.authCheck()

    this.setState({ loaded: true })

    // ログインしてなければログイン画面へとばす
    if (!this.props.auth.isLogin) {
      this.props.history.push('/login?redirectUrl=' + window.location)
    }
  }

  render(): JSX.Element {
    if (!this.state.loaded) {
      return <React.Fragment>Loading...</React.Fragment>
    } else {
      return <React.Fragment>{this.props.children}</React.Fragment>
    }
  }
}

const mapStateToProps = (state) => {
  return {
    auth: state.auth,
  }
}

const mapDispatchToProps = { authCheck }

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(AuthCheck))
