import { connect } from 'react-redux'
import CSRFToken from '../../components/Elements/CSRFToken'

const mapStateToProps = state => {
  return {
    csrf: state.auth.csrf,
  }
}
const mapDispatchToProps = () => ({})
export default connect(mapStateToProps, mapDispatchToProps)(CSRFToken)
