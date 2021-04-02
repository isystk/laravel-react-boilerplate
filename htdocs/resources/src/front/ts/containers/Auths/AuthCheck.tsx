import { connect } from 'react-redux'
import AuthCheck from '../../components/Auths/AuthCheck'

const mapStateToProps = (state, ownProps) => {
  console.log(state, ownProps)
  return {
    session: ownProps.session,
  }
}

const mapDispatchToProps = {}

export default connect(mapStateToProps, mapDispatchToProps)(AuthCheck)
