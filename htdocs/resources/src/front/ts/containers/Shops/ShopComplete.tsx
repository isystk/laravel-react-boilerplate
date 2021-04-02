import { connect } from 'react-redux'
import ShopComplete from '../../components/Shops/ShopComplete'
import { push } from 'connected-react-router'

const mapStateToProps = state => {
  return {
    auth: state.auth,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    },
  }
}

const mapDispatchToProps = { push }

export default connect(mapStateToProps, mapDispatchToProps)(ShopComplete)
