import React from 'react'
import { connect } from 'react-redux'
import { ConnectedRouter } from 'connected-react-router'
import
{
  setSession,
  setName,
  setEmail,
  setRemember,
  setCSRF
} from './actions/auth'
import routes from './routes'

interface IProps {
  setCSRF;
  responseSession;
  history;
  csrf;
  setSession;
  setName;
  setEmail;
  setRemember;
}

class ReactRoot extends React.Component<IProps>
{
  constructor(props)
  {
    super(props)
    props.setSession(props.responseSession)
    // if (window.laravelErrors['name'] === undefined
    //   && window.laravelErrors['email'] === undefined
    //   && window.laravelErrors['password'] === undefined) {
    //   this.props.setName('')
    //   this.props.setEmail('')
    //   this.props.setRemember(false)
    // }
    const token = document.head.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
    if (token) {
      this.props.setCSRF(token.content)
    }

  }

  render()
  {
    return (
      <ConnectedRouter history= { this.props.history } >
        { routes(this.props.responseSession) }
      </ConnectedRouter>
    )
  }
}

const mapStateToProps = state => ({
  csrf: state.csrf,
})

const mapDispatchToProps = dispatch => ({
  setCSRF: (csrf) => dispatch(setCSRF(csrf)),
  setSession: (session) => dispatch(setSession(session)),
  setName: (name) => dispatch(setName(name)),
  setEmail: (email) => dispatch(setEmail(email)),
  setRemember: (remember) => dispatch(setRemember(remember)),
})

export default connect(mapStateToProps, mapDispatchToProps)(ReactRoot)
