import * as React from 'react'
import { connect } from 'react-redux'
import * as _ from 'lodash'
import { push } from "connected-react-router"
import { API_ENDPOINT } from '../../common/constants/api'
import { URL } from '../../common/constants/url'
import CSRFToken from '../Elements/CSRFToken'

import { readCarts, removeCart } from '../../actions'
import { NavDropdown, Form } from 'react-bootstrap'
import { Auth, Carts } from '../../store/StoreTypes'

interface IProps {
  auth: Auth
  carts: Carts
  push
  readCarts
  removeCart
}

export class MyCart extends React.Component<IProps> {

  constructor(props) {
    super(props);

    // マイカートデータを取得する
    this.props.readCarts()
  }

  renderCarts(): JSX.Element {
    return _.map(this.props.carts.data, (cart, index) => (
      <div className="block01_item" key={index}>
          <img src={`/uploads/stock/${cart.imgpath}`} alt="" className="block01_img" />
          <p>{cart.name} </p>
          <p className="c-red mb20">{cart.price}円 </p>
          <Form id="shop-check" action="/cartdelete" method="POST" >
            <CSRFToken />
            <input type="hidden" name="stock_id" value="{{ $my_cart->stock->id }}" />
            <input type="submit" value="カートから削除する" className="btn-01" onClick={() => {
              this.props.removeCart(cart.id);
            }} />
          </Form>
      </div>
      ))
  }

  render(): JSX.Element {
    return (
      <div className="contentsArea">
          <h2 className="heading02">{this.props.auth.name}さんのカートの中身</h2>

          <div className="">
              <p className="text-center mt20">{ this.props.carts.message }</p><br/>

              {
                (() => {
                  if (this.props.carts.data.length === 0) {
                    return <p className="text-center">カートに商品がありません。</p>
                  } else {
                    return (
                      <>
                        <div className="block01">
                          {this.renderCarts()}
                        </div>
                        <div className="block02">
                            <p>合計個数：xx個</p>
                            <p style={{fontSize:'1.2em', fontWeight: 'bold'}}>合計金額:cccc円</p>
                        </div>
                        <Form id="shop-check" action="/checkout" method="POST" >
                            <CSRFToken />
                            {/* <script src="https://checkout.stripe.com/checkout.js" className="stripe-button" data-key="{{ env('STRIPE_KEY') }}" data-amount="{{ $sum }}" data-name="Lara EC" data-label="決済をする" data-description="お支払い完了後にメールにてご連絡いたします。" data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-currency="JPY">
                            </script> */}決済をする
                        </Form>
                      </>
                    )
                  }
                })()
              }

              <p className="mt30 ta-center"><a href={URL.TOP} className="text-danger btn">商品一覧へ</a></p>
          </div>
      </div>
    )
  }
}

const mapStateToProps = (state, ownProps) => {
  const carts = state.carts
  console.log(carts);
  return {
    auth: state.auth,
    carts: carts,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    }
  }
}

const mapDispatchToProps = { push, readCarts, removeCart }

export default connect(mapStateToProps, mapDispatchToProps)(MyCart)
