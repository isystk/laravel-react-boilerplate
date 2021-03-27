import React from 'react'
import { connect } from 'react-redux'
import { ConnectedRouter } from 'connected-react-router'
import { setSession, setName, setEmail, setRemember, setCSRF, readConsts } from './actions'
import routes from './routes'

type Props = {
  setCSRF: (arg0: string) => void
  responseSession: string
  history: any
  csrf: any
  setSession: (arg0: any) => void
  setName: (arg0: string) => void
  setEmail: (arg0: string) => void
  setRemember: (arg0: string) => void
  readConsts: () => void
}

class ReactRoot extends React.Component<Props> {
  constructor(props: Props | Readonly<Props>) {
    super(props)
    // セッションのセット
    this.props.setSession(props.responseSession)
    // 定数のセット
    this.props.readConsts()
    // if (window.laravelErrors['name'] === undefined
    //   && window.laravelErrors['email'] === undefined
    //   && window.laravelErrors['password'] === undefined) {
    //   this.props.setName('')
    //   this.props.setEmail('')
    //   this.props.setRemember(false)
    // }
    // CSRFのセット
    const token = document.head.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
    if (token) {
      this.props.setCSRF(token.content)
    }
  }

  render() {
    return <ConnectedRouter history={this.props.history}>{routes(this.props.responseSession)}</ConnectedRouter>
  }
}

const mapStateToProps = (state: { csrf: string }) => ({
  csrf: state.csrf,
})

const mapDispatchToProps = (
  dispatch: (arg0: {
    type: string
    payload?:
      | { csrf: string }
      | { id: number; name: string }
      | { name: string }
      | { email: string }
      | { remember: string }
    response?: any
  }) => any,
) => ({
  setCSRF: (csrf: string) => dispatch(setCSRF(csrf)),
  setSession: (session: any) => dispatch(setSession(session)),
  setName: (name: string) => dispatch(setName(name)),
  setEmail: (email: string) => dispatch(setEmail(email)),
  setRemember: (remember: string) => dispatch(setRemember(remember)),
  readConsts: async () => dispatch(await readConsts()),
})

export default connect(mapStateToProps, mapDispatchToProps)(ReactRoot)
