import * as React from "react";
import { connect } from "react-redux";
import * as _ from "lodash";
import { push } from "connected-react-router";
import { API_ENDPOINT } from "../../common/constants/api";
import { URL } from "../../common/constants/url";
import CSRFToken from "../Elements/CSRFToken";
import { Elements, StripeProvider } from 'react-stripe-elements';

import { readCarts, removeCart } from "../../actions";
import { NavDropdown, Form } from "react-bootstrap";
import { Auth, Consts, Carts } from "../../store/StoreTypes";

interface IProps {
  auth: Auth;
  consts: Consts;
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
    return _.map(this.props.carts.data, (cart, index) => (
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
    ));
  }

  render(): JSX.Element {
    return (
      <div className="contentsArea">
        <h2 className="heading02">{this.props.auth.name}さんのカートの中身</h2>

        <div className="">
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
                      合計金額:{this.props.carts.sum}円
                    </p>
                  </div>
                  <StripeProvider apiKey={this.props.consts.stripe_key.data}>
                    <div className="container">
                      <h3 className="my-4">決済をする</h3>
                      <Form id="shop-check" action="/checkout" method="POST">
                        <CSRFToken />
                      </Form>
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

  return {
    auth: state.auth,
    consts: state.consts,
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
