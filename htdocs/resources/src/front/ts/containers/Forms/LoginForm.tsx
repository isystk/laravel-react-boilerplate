import { connect } from 'react-redux'
import LoginForm from '../../components/Forms/LoginForm'
import { setEmail, setRemember } from '../../actions/auth'

const mapStateToProps = state => ({
  email: state.email,
  remember: state.remember,
})

const mapDispatchToProps = dispatch => ({
  setEmail: email => dispatch(setEmail(email)),
  setRemember: remember => dispatch(setRemember(remember)),
})

export default connect(mapStateToProps, mapDispatchToProps)(LoginForm)
