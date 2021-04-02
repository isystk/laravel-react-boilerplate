import { connect } from 'react-redux'
import { push } from 'connected-react-router'
import ContactComplete from '../../components/Contacts/ContactComplete'

const mapStateToProps = () => {
  return {}
}

const mapDispatchToProps = {
  push,
}

export default connect(mapStateToProps, mapDispatchToProps)(ContactComplete)
