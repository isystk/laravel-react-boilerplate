import { connect } from 'react-redux'
import CardTemplate from '../components/CardTemplate'
import { setPrams } from '../actions/auth'

const mapStateToProps = () => ({})

const mapDispatchToProps = dispatch => ({
  setPrams: request => dispatch(setPrams(request)),
})

export default connect(mapStateToProps, mapDispatchToProps)(CardTemplate)
