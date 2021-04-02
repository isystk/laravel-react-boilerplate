import * as React from 'react'
import { URL } from '../../common/constants/url'

type Props = {
  push
}

export class ContactCreate extends React.Component<Props> {
  constructor(props) {
    super(props)
  }

  render(): JSX.Element {
    return (
      <div className="contentsArea">
        <h2 className="heading02" style={{ color: '#555555', fontSize: '1.2em', padding: '24px 0px' }}>
          お問い合わせが完了しました。
        </h2>

        <div className="ta-center">
          <p>お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。</p>
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

export default ContactCreate
