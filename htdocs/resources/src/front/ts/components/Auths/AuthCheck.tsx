import * as React from "react";
import { connect } from "react-redux";
import { Redirect } from "react-router";

interface IProps {
  session;
}

export class AuthCheck extends React.Component<IProps> {
  constructor(props) {
    super(props);
  }

  render() {
    // ログインしてなければログイン画面へとばす
    if (this.props.session.id === undefined) {
      return <Redirect to="/login" />;
    }

    // // 新規会員登録後、メール確認が未完了の場合
    // if(this.props.session.email_verified_at===null)
    // {
    //   return <Redirect to="/email/verify" />;
    // }

    // ログイン済みの場合
    return <React.Fragment>{this.props.children}</React.Fragment>;
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    session: ownProps.session,
  };
};

const mapDispatchToProps = {};

export default connect(mapStateToProps, mapDispatchToProps)(AuthCheck);
