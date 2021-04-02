import { connect } from 'react-redux'
import CheckoutForm from '../../components/Forms/CheckoutForm'
import { injectStripe } from 'react-stripe-elements'
import { push } from 'connected-react-router'

const mapStateToProps = (state, ownProps) => {
  console.log(state.auth)
  return {
    amount: ownProps.amount,
    username: ownProps.username,
  }
}

const mapDispatchToProps = {
  push,
}

export default injectStripe(connect(mapStateToProps, mapDispatchToProps)(CheckoutForm))
