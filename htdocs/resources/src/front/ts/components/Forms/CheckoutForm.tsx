import React from 'react'
import { connect } from 'react-redux'
import { injectStripe, CardNumberElement, CardExpiryElement, CardCVCElement } from 'react-stripe-elements'
import { Button, Form, FormGroup, Label, Input, FormFeedback } from 'reactstrap'
import { Formik } from 'formik'
import * as Yup from 'yup'
import { API_ENDPOINT } from '../../common/constants/api'
import { URL } from '../../common/constants/url'
import CSRFToken from '../Elements/CSRFToken'
import { API } from '../../utilities'
import { push } from 'connected-react-router'

interface IProps {
  stripe
  elements
  push
}

class CheckoutForm extends React.Component<IProps> {
  handlePayment = async values => {
    // alert(JSON.stringify(values));

    //paymentIntentの作成を（ローカルサーバ経由で）リクエスト
    const response = await API.post(API_ENDPOINT.CREATE_PAYMENT, { amount: values.amount, username: values.username })

    //レスポンスからclient_secretを取得
    const client_secret = response.client_secret

    //client_secretを利用して（確認情報をStripeに投げて）決済を完了させる
    const confirmRes = await this.props.stripe.confirmCardPayment(client_secret, {
      payment_method: {
        // card: this.props.elements.getElement('card'),
        card: this.props.elements.getElement('cardNumber'),
        billing_details: {
          name: values.username,
        },
      },
    })

    if (confirmRes.paymentIntent.status === 'succeeded') {
      // 決算処理が完了したら、マイカートから商品を削除する。
      const response = await API.post(API_ENDPOINT.CHECKOUT, {})

      if (response.result) {
        alert('決済完了')

        this.props.push(URL.TOP)
      }
    }
  }

  cardNumberRef: any
  submit: any

  render() {
    console.log(this.props.stripe)
    return (
      <div className="col-8">
        <p>決済情報の入力</p>
        <Formik
          initialValues={{ amount: 100, username: 'TARO YAMADA' }}
          onSubmit={values => this.handlePayment(values)}
          validationSchema={Yup.object().shape({
            amount: Yup.number()
              .min(1)
              .max(1000),
          })}
        >
          {({ handleChange, handleSubmit, handleBlur, values, errors, touched }) => (
            <Form onSubmit={handleSubmit}>
              <CSRFToken />
              <FormGroup>
                <Label>金額</Label>
                <Input
                  type="text"
                  name="amount"
                  value={values.amount}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  invalid={Boolean(touched.amount && errors.amount)}
                />
                <FormFeedback>{errors.amount}</FormFeedback>
              </FormGroup>
              <FormGroup>
                <Label>利用者名</Label>
                <Input
                  type="text"
                  name="username"
                  value={values.username}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  invalid={Boolean(touched.username && errors.username)}
                />
                <FormFeedback>{errors.username}</FormFeedback>
              </FormGroup>
              {/* <CardElement
                                    className="bg-light p-3"
                                    hidePostalCode={true}
                                /> */}
              <legend className="col-form-label">カード番号</legend>
              <CardNumberElement ref={this.cardNumberRef} className="p-2 bg-light" />
              <legend className="col-form-label">有効期限</legend>
              <CardExpiryElement className="p-2 bg-light" />
              <legend className="col-form-label">セキュリティーコード</legend>
              <CardCVCElement className="p-2 bg-light" />

              <Button onClick={this.submit} className="my-3" color="primary">
                購入
              </Button>
            </Form>
          )}
        </Formik>
      </div>
    )
  }
}

const mapStateToProps = () => ({})

const mapDispatchToProps = {
  push,
}

export default injectStripe(connect(mapStateToProps, mapDispatchToProps)(CheckoutForm))
