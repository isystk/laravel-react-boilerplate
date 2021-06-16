import { connect } from 'react-redux'
import ResetForm from '../../components/Forms/ResetForm'
import { setEmail } from '../../actions/auth'

const mapStateToProps = state => ({
  email: state.email,
})

const mapDispatchToProps = dispatch => ({
  setEmail: email => dispatch(setEmail(email)),
})

export default connect(mapStateToProps, mapDispatchToProps)(ResetForm)
