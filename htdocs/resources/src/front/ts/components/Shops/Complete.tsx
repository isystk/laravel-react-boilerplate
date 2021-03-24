import * as React from "react";
import { connect } from "react-redux";
import { push } from "connected-react-router";

import { Auth } from "../../store/StoreTypes";

interface IProps {
  auth: Auth;
  push;
}

export class Complete extends React.Component<IProps> {
  render(): JSX.Element {
    return (
      <div className="contentsArea">
          <h2 className="heading02" style={{color:"#555555", fontSize:"1.2em", padding:"24px 0px"}}>
              {this.props.auth.name}さんご購入ありがとうございました
          </h2>
          <div className="ta-center">
              <p>ご登録頂いたメールアドレスへ決済情報をお送りしております。お手続き完了次第商品を発送致します。<br/>
                  (メールは送信されません)</p>
              <a href="/" className="btn text-danger mt40">商品一覧へ</a>
          </div>
      </div>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    auth: state.auth,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    },
  };
};

const mapDispatchToProps = { push };

export default connect(mapStateToProps, mapDispatchToProps)(Complete);
