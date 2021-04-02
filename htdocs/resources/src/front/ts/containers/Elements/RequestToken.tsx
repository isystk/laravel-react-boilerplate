import { connect } from 'react-redux'
import RequestToken from '../../components/Elements/RequestToken'

const mapStateToProps = state => ({
  params: state.params,
})
const mapDispatchToProps = () => ({})

export default connect(mapStateToProps, mapDispatchToProps)(RequestToken)
