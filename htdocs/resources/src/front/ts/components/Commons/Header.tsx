import * as React from "react";
import { connect } from "react-redux";
import { Link } from "react-router-dom";
import { URL } from "../../common/constants/url";
import { NavDropdown, Form } from 'react-bootstrap'
import CSRFToken from '../Elements/CSRFToken'

interface IProps {
  auth
}

class CommonHeader extends React.Component<IProps> {

  constructor(props) {
    super(props);
  }

  renderLogin(): JSX.Element {

    const { auth, name } = this.props.auth

    return (
      <ul className="navbar-nav ml-auto">
        {
          (() => {
            if (auth) {
              // ログイン済みの場合
              return (
                <>
                <NavDropdown id="logout-nav" title={name+' 様'}>
                    <NavDropdown.Item href="/logout" onClick={(e) => {
                      e.preventDefault();
                      const element: HTMLFormElement = document.getElementById('logout-form') as HTMLFormElement
                      if(element) {
                        element.submit();
                      }
                    }}>ログアウト</NavDropdown.Item>
                    <Form id="logout-form" action="/logout" method="POST" style={{display:'none'}}>
                        <CSRFToken />
                    </Form>
                    <NavDropdown.Item href="/mycart" onClick={this.mycartSubmit}>カートを見る</NavDropdown.Item>
                    <Form id="mycart-form" action="/mycart" method="POST" style={{display:'none'}}>
                        <CSRFToken />
                    </Form>
                </NavDropdown>

                <a href="#" onClick={this.mycartSubmit}>
                    <img src="/assets/front/image/cart.png" className="cartImg ml-3" />
                </a>

                </>
              )
            } else {
              // 未ログインの場合
              return (
                <>
                  <li className="nav-item">
                      <a className="btn btn-danger mr-3" href="/login">ログイン</a>
                  </li>
                  <li className="nav-item">
                      <a className="btn btn-link text-danger" href="/register">新規登録</a>
                  </li>
                </>
              )
            }
          })()
        }

        <li className="nav-item">
            <a className="btn btn-link text-danger" href="/contact">
                お問い合わせ
            </a>
        </li>
      </ul>
    )
  }

  mycartSubmit(currentEvent){
    currentEvent.preventDefault();
    const element: HTMLFormElement = document.getElementById('mycart-form') as HTMLFormElement
    if(element) {
      element.submit();
    }
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

                    {this.renderLogin()}

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
