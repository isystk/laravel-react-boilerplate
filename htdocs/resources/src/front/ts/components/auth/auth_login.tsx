import * as React from "react";
import { connect, MapStateToProps, MapDispatchToProps } from "react-redux";
import { Field, reduxForm } from "redux-form";
import { Link, withRouter } from "react-router-dom";
import { URL } from "../../common/constants/url";

import { Auth } from "../../store/StoreTypes";
import { authCheck, authLogin } from "../../actions";

interface IProps {
  auth: Auth;
  authCheck;
  authLogin;
  history;
  error;
  handleSubmit;
  pristine;
  submitting;
  invalid;
}

interface IState {
  redirectUrl: string
}

export class AuthLogin extends React.Component<IProps, IState> {
  constructor(props) {
    super(props);
    this.onSubmit = this.onSubmit.bind(this);
    this.state = {
      redirectUrl: '/'
    }
  }

  componentWillMount() {
    this.checkAuth();
    const params = this.getParams();
    this.setState({
      redirectUrl: params.redirectUrl as string
    });
  }

  getParams(): any {
    //?を除去
    const urlParamStr = window.location.search.substring(1)
    let params = {}
    //urlパラメータをオブジェクトにまとめる
    urlParamStr.split('&').forEach( param => {
      const temp = param.split('=')
      //pramsオブジェクトにパラメータを追加
      params = {
        ...params,
        [temp[0]]: temp[1]
      }
    });
    return params;
  }

  async checkAuth() {

    await this.props.authCheck();

    // 既にログイン済みの場合は元のページへとばす
    if (this.props.auth.isLogin) {
      if (this.state.redirectUrl) {
        location.href = this.state.redirectUrl;
      } else {
        this.props.history.push(URL.HOME);
      }
    }
  }

  async onSubmit(values): Promise<void> {
    await this.props.authLogin(values);

    const { auth } = this.props;

    if (auth.isLogin) {
      if (this.state.redirectUrl) {
        location.href = this.state.redirectUrl;
      } else {
        this.props.history.push(URL.HOME);
      }
    }

  }

  render(): JSX.Element {
    // pristineは、フォームが未入力状態の場合にtrueを返す
    // submittingは、既にSubmit済みの場合にtrueを返す
    const { error, handleSubmit, pristine, submitting, invalid } = this.props;
    const style = {
      margin: 20,
    };
    return (
      <React.Fragment>
      </React.Fragment>
    );
  }
}

const validate = (values) => {
  const errors = {
    loginId: "",
    password: "",
  };
  if (!values.loginId) errors.loginId = "ログインIDを入力して下さい";
  if (!values.password) errors.password = "パスワードを入力して下さい";
  return errors;
};

const mapStateToProps = (state, ownProps) => {
  return {
    auth: state.auth
  };
};

const mapDispatchToProps = { authCheck, authLogin };

export default withRouter(connect(
  mapStateToProps,
  mapDispatchToProps
)(reduxForm({ validate, form: "authLoginForm" })(AuthLogin)));
