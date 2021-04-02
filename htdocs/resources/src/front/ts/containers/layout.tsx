import { connect } from 'react-redux'
import Layout from '../components/layout'

const mapStateToProps = (state: { parts: any; auth: any }) => {
  return {
    parts: state.parts,
    auth: state.auth,
  }
}

const mapDispatchToProps = {}

export default connect(mapStateToProps, mapDispatchToProps)(Layout)
