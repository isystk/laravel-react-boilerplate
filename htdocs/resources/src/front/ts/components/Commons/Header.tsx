import * as React from "react";
import { connect } from "react-redux";
import { Link } from "react-router-dom";
import { URL } from "../../common/constants/url";

class CommonHeader extends React.Component {

  constructor(props) {
    super(props);
  }

  render(): JSX.Element {

    return (
      <React.Fragment>
      <header className="header shadow-sm">
          <nav className="navbar navbar-expand-md navbar-light bg-white headerNav">
              <a className="header_logo" href="/">
                  <img src="/assets/front/image/logo.png" alt="" className="" />
              </a>

              <div className="" id="navbarSupportedContent">
                  <ul className="navbar-nav mr-auto">

                  </ul>

                  <ul className="navbar-nav ml-auto">
                      <li className="nav-item">
                          <a className="btn btn-danger mr-3" href="/login">ログイン</a>
                      </li>
                      <li className="nav-item">
                          <a className="btn btn-link text-danger" href="/register">新規登録</a>
                      </li>
                      <li className="nav-item">
                          <a className="btn btn-link text-danger" href="#">
                              お問い合わせ
                          </a>
                      </li>

                      {/*
                      <li className="nav-item dropdown">
                          <a id="navbarDropdown" className="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                              {{ Auth::user()->name }} <span className="caret"></span>
                          </a>

                          <div className="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                              <a className="dropdown-item" href="#" onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                  {{ __('ログアウト') }}
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>

                              <a className="dropdown-item" href="#" onclick="event.preventDefault();
                                                      document.getElementById('shop-form').submit();">
                                  カートを見る
                              </a>

                              <form id="shop-form" action="{{ route('shop.mycart') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>

                          </div>
                      </li>

                      <a href="#" onclick="event.preventDefault();
                                                      document.getElementById('shop-form').submit();">
                          <img src="{{ asset('/assets/front/image/cart.png') }}" className="cartImg ml-3">
                      </a>
                      <li className="nav-item">
                          <a className="btn btn-link text-danger" href="{{ route('contact.index') }}">
                              お問い合わせ
                          </a>
                      </li>

                       */}

                  </ul>
              </div>
          </nav>
      </header>
      </React.Fragment>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    parts: state.parts,
    auth: state.auth
  };
};

const mapDispatchToProps = {  };

export default connect(mapStateToProps, mapDispatchToProps)(CommonHeader);
