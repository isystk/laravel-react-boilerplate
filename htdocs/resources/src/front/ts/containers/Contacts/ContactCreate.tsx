import { connect } from 'react-redux'
import { push } from 'connected-react-router'
import ContactCreate from '../../components/Contacts/ContactCreate'

const mapStateToProps = state => {
  const { auth, consts } = state
  return {
    auth,
    consts,
  }
}

const mapDispatchToProps = {
  push,
}

export default connect(mapStateToProps, mapDispatchToProps)(ContactCreate)
