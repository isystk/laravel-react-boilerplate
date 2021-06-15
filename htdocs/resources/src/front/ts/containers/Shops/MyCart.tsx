import { connect } from 'react-redux'
import MyCart from '../../components/Shops/MyCart'
import { push } from 'connected-react-router'
import { readCarts, removeCart, showOverlay, hideOverlay } from '../../actions'

const mapStateToProps = state => {
  const { stripe_key } = state.consts

  return {
    auth: state.auth,
    stripe_key: stripe_key.data,
    carts: state.carts,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    },
  }
}

const mapDispatchToProps = { push, readCarts, removeCart, showOverlay, hideOverlay }

export default connect(mapStateToProps, mapDispatchToProps)(MyCart)
