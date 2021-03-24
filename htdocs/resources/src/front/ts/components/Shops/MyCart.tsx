import * as React from "react";
import { connect } from "react-redux";
import { push } from "connected-react-router";
import { URL } from "../../common/constants/url";
import CSRFToken from "../Elements/CSRFToken";
import { Elements, StripeProvider } from 'react-stripe-elements';
import CheckoutForm from '../Forms/CheckoutForm';

import { readCarts, removeCart } from "../../actions";
import { NavDropdown, Form } from "react-bootstrap";
import { Auth, Consts, Const, Carts, Cart } from "../../store/StoreTypes";

interface IProps {
  auth: Auth;
  stripe_key: string;
  carts: Carts;
  push;
  readCarts;
  removeCart;
}

export class MyCart extends React.Component<IProps> {
  constructor(props) {
    super(props);

    // マイカートデータを取得する
    this.props.readCarts();
  }

  renderCarts(): JSX.Element {
    return (
    <>
      {
      this.props.carts.data.map((cart, index) => (
        <div className="block01_item" key={index}>
          <img
            src={`/uploads/stock/${cart.imgpath}`}
            alt=""
            className="block01_img"
          />
          <p>{cart.name} </p>
          <p className="c-red mb20">{cart.price}円 </p>
          <input
            type="button"
            value="カートから削除する"
            className="btn-01"
            onClick={() => {
              this.props.removeCart(cart.id);
            }}
          />
        </div>
      ))
    }
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
              return <p className="text-center">カートに商品がありません。</p>;
            } else {
              return (
                <>
                  <div className="block01">{this.renderCarts()}</div>
                  <div className="block02">
                    <p>合計個数：{this.props.carts.count}個</p>
                    <p style={{ fontSize: "1.2em", fontWeight: "bold" }}>
                      合計金額：{this.props.carts.sum}円
                    </p>
                  </div>
                  <StripeProvider apiKey={this.props.stripe_key}>
                    <div className="container">
                      <h3 className="my-4">決済をする</h3>
                      <Elements>
                        <CheckoutForm />
                      </Elements>
                    </div>
                  </StripeProvider>
                </>
              );
            }
          })()}

          <p className="mt30 ta-center">
            <a href={URL.TOP} className="text-danger btn">
              商品一覧へ
            </a>
          </p>
        </div>
      </div>
    );
  }
}

const mapStateToProps = (state, ownProps) => {

  const { stripe_key } = state.consts;

  return {
    auth: state.auth,
    stripe_key: stripe_key.data,
    carts: state.carts,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    },
  };
};

const mapDispatchToProps = { push, readCarts, removeCart };

export default connect(mapStateToProps, mapDispatchToProps)(MyCart);
