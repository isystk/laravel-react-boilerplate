import { connect } from 'react-redux'
import RegisterForm from '../../components/Forms/RegisterForm'
import { setName, setEmail } from '../../actions/auth'

const mapStateToProps = state => ({
  name: state.name,
  email: state.email,
})

const mapDispatchToProps = dispatch => ({
  setName: name => dispatch(setName(name)),
  setEmail: email => dispatch(setEmail(email)),
})

export default connect(mapStateToProps, mapDispatchToProps)(RegisterForm)
