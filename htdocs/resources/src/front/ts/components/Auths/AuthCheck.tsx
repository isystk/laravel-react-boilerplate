import * as React from "react";
import { connect } from "react-redux";
import { push } from 'connected-react-router'

interface IProps {
  session;
  push;
}

export class AuthCheck extends React.Component<IProps> {
  constructor(props) {
    super(props);
  }

  render() {

    // ログインしてなければログイン画面へとばす
    if (this.props.session.id===undefined) {
      console.log("Redirect!", "/login");
      this.props.push('/login');
      return;
    }

    // 新規会員登録後、メール確認が未完了の場合
    if(this.props.session.email_verified_at===null)
    {
      console.log("Redirect!", "/email/verify");
      this.props.push('/email/verify');
      return;
    }

    // ログイン済みの場合
    return (
    <React.Fragment>{this.props.children}</React.Fragment>
    );
  }
}

const mapStateToProps = (state, ownProps) => {
  return {
    session: ownProps.session,
  };
};

const mapDispatchToProps = { push };

export default connect(mapStateToProps, mapDispatchToProps)(AuthCheck);