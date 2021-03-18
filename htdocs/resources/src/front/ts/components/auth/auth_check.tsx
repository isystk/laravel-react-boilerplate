import * as React from 'react'
import { connect, MapStateToProps, MapDispatchToProps } from 'react-redux'
import { Field, reduxForm } from 'redux-form'
import { Auth } from '../../store/StoreTypes'
import { Link, withRouter } from 'react-router-dom'

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

  UNSAFE_componentWillMount() {
    this.checkAuth()
  }

  async checkAuth() {
    await this.props.authCheck()

    this.setState({ loaded: true })

    // ログインしてなければログイン画面へとばす
    if (!this.props.auth.isLogin) {
      this.props.history.push('/login?redirectUrl=' + window.location)
    }
  }

  render() {
    if (!this.state.loaded) {
      return <React.Fragment>Loading...</React.Fragment>
    } else {
      return <React.Fragment>{this.props.children}</React.Fragment>
    }
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    auth: state.auth,
  }
}

const mapDispatchToProps = { authCheck }

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(AuthCheck))
