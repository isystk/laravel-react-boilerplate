import * as React from 'react'
import { connect } from 'react-redux'
import { push } from 'connected-react-router'
import { URL } from '../../common/constants/url'
import { Elements, StripeProvider } from 'react-stripe-elements'
import CheckoutForm from '../Forms/CheckoutForm'
import Modal from '../Commons/Modal'
import { Row, Col, Button } from 'react-bootstrap'

import { readCarts, removeCart, showOverlay } from '../../actions'
import { Auth, Carts } from '../../store/StoreTypes'

interface IProps {
  auth: Auth
  stripe_key: string
  carts: Carts
  push
  readCarts
  removeCart
  showOverlay
}

export class MyCart extends React.Component<IProps> {
  constructor(props) {
    super(props)

    // マイカートデータを取得する
    this.props.readCarts()
  }

  renderCarts(): JSX.Element {
    return (
      <>
        {this.props.carts.data.map((cart, index) => (
          <div className="block01_item" key={index}>
            <img src={`/uploads/stock/${cart.imgpath}`} alt="" className="block01_img" />
            <p>{cart.name} </p>
            <p className="c-red mb20">{cart.price}円 </p>
            <input
              type="button"
              value="カートから削除する"
              className="btn-01"
              onClick={() => {
                this.props.removeCart(cart.id)
              }}
            />
          </div>
        ))}
      </>
    )
  }

  render(): JSX.Element {
    return (
      <div className="contentsArea">
        <h2 className="heading02">{this.props.auth.name}さんのカートの中身</h2>

        <div>
          <p className="text-center mt20">{this.props.carts.message}</p>
          <br />

          {(() => {
            if (this.props.carts.data.length === 0) {
              return <p className="text-center">カートに商品がありません。</p>
            } else {
              return (
                <>
                  <div className="block01">{this.renderCarts()}</div>
                  <div className="block02">
                    <p>合計個数：{this.props.carts.count}個</p>
                    <p style={{ fontSize: '1.2em', fontWeight: 'bold' }}>合計金額：{this.props.carts.sum}円</p>
                  </div>
                  <div style={{margin: "40px 15px", textAlign: "center"}}>
                    <Button
                      type="submit"
                      variant="primary"
                      onClick={e => {
                        e.preventDefault()
                        this.props.showOverlay()
                      }}
                    >
                      決済をする
                    </Button>
                  </div>
                  <Modal>
                    <StripeProvider apiKey={this.props.stripe_key}>
                      <Elements>
                        <CheckoutForm amount={this.props.carts.sum} username={this.props.auth.email} />
                      </Elements>
                    </StripeProvider>
                  </Modal>
                </>
              )
            }
          })()}

          <p className="mt30 ta-center">
            <a href={URL.TOP} className="text-danger btn">
              商品一覧へ
            </a>
          </p>
        </div>
      </div>
    )
  }
}

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

const mapDispatchToProps = { push, readCarts, removeCart, showOverlay }

export default connect(mapStateToProps, mapDispatchToProps)(MyCart)
