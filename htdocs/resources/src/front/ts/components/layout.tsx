import * as React from "react";
import { connect, MapStateToProps, MapDispatchToProps } from "react-redux";
import * as _ from "lodash";
import { Link } from "react-router-dom";
import { URL } from "../common/constants/url";
import CommonHeader from "./common/common_header";
import CommonFooter from "./common/common_footer";
import { Auth, Parts } from "../store/StoreTypes";

import { authCheck, authLogout } from "../actions";

interface IProps {
  auth: Auth;
  parts: Parts;
  authCheck;
  authLogout;
}

interface IState {
}

class Layout extends React.Component<IProps, IState> {

  constructor(props) {
    super(props);
    this.logoutClick = this.logoutClick.bind(this);
  }

  componentDidMount(): void {
    this.props.authCheck();
  }

  async logoutClick() {
    await this.props.authLogout();
    location.reload();
  }

  logoutLink(): JSX.Element {

    const {auth} = this.props;

    if (auth.isLogin) {
      return (<a onClick={this.logoutClick}>ログアウト</a>);
    }
    return (<Link to={URL.LOGIN}>ログイン</Link>);
  }

  render(): JSX.Element {

// this.props.childrenに親のPropsを渡す
//     const parentProp = {showMainVisual: this.showMainVisual}
//     const childrenWithProps = Children.map(
//       this.props.children,
//       (child) => {
//         return React.cloneElement(child as React.ReactElement<any>, parentProp);
//       },
//     );

    return (
      <React.Fragment>
        <CommonHeader />

        {
          (this.props.parts.isShowMv) &&
          <div className="mv">
            <img alt="main-visual" src="/assets/img/main-visual.jpg" width="100%"/>
            <div className="intro">
              <div className="box"><p className="title">フロントエンド サンプルアプリケーション</p></div>
            </div>
          </div>
        }

        {// ナビゲーション（PC用）
        }
        <div id="pc-menu">
          <div className="wrapper">
            <nav>
              <ul>
                <li><Link to={URL.HOME}>HOME</Link></li>
                <li><Link to={URL.MEMBER}>マイページ</Link></li>
                <li>{this.logoutLink()}</li>
              </ul>
            </nav>
          </div>
        </div>

        <div className="contents">
          <div className="wrapper">
            { this.props.children }
          </div>
        </div>

        <CommonFooter />
        {(this.props.parts.isShowLoading) &&
          <div id="site_loader_overlay"><div className="site_loader_spinner" ></div></div>
        }
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

const mapDispatchToProps = { authCheck, authLogout };

export default connect(mapStateToProps, mapDispatchToProps)(Layout);
