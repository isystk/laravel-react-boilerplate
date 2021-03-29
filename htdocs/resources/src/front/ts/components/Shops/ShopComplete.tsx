import * as React from 'react'
import { connect } from 'react-redux'
import { push } from 'connected-react-router'

import { URL } from '../../common/constants/url'
import { Auth } from '../../store/StoreTypes'

type Props = {
  auth: Auth
  push
}

export class ShopComplete extends React.Component<Props> {
  render(): JSX.Element {
    return (
      <div className="contentsArea">
        <h2 className="heading02" style={{ color: '#555555', fontSize: '1.2em', padding: '24px 0px' }}>
          {this.props.auth.name}さんご購入ありがとうございました
        </h2>
        <div className="ta-center">
          <p>
            ご登録頂いたメールアドレスへ決済情報をお送りしております。お手続き完了次第商品を発送致します。
            <br />
            (メールは送信されません)
          </p>
          <a
            href={URL.TOP}
            className="btn text-danger mt40"
            onClick={e => {
              e.preventDefault()
              this.props.push(URL.TOP)
            }}
          >
            商品一覧へ戻る
          </a>
        </div>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    auth: state.auth,
    url: {
      pathname: state.router.location.pathname,
      search: state.router.location.search,
      hash: state.router.location.hash,
    },
  }
}

const mapDispatchToProps = { push }

export default connect(mapStateToProps, mapDispatchToProps)(ShopComplete)
