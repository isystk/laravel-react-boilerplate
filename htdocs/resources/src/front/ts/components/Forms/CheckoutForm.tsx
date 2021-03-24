import React from 'react';
import { CardElement, injectStripe, CardNumberElement, CardExpiryElement, CardCVCElement, Elements } from 'react-stripe-elements';
import { Button, Form, FormGroup, Label, Input, FormFeedback } from 'reactstrap';
import { Formik } from 'formik'
import * as Yup from 'yup';

class CheckoutForm extends React.Component {

    handlePayment = async (values) => {

        // alert(JSON.stringify(values));

        const headers = new Headers();
        headers.set('Content-type', 'application/json');
        // headers.set('Access-Control-Allow-Origin', '*');

        //paymentIntentの作成を（ローカルサーバ経由で）リクエスト
        const createRes = await fetch('http://localhost:9000/createPaymentIntent', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ amount: values.amount, username: values.username })
        })

        //レスポンスからclient_secretを取得
        const responseJson = await createRes.json();
        const client_secret = responseJson.client_secret;

        //client_secretを利用して（確認情報をStripeに投げて）決済を完了させる
        const confirmRes = await this.props.stripe.confirmCardPayment(client_secret, {
            payment_method: {
                // card: this.props.elements.getElement('card'),
                card: this.props.elements.getElement('cardNumber'),
                billing_details: {
                    name: values.username,
                }
            }
        });

        if (confirmRes.paymentIntent.status === "succeeded") {
            alert("決済完了");
        }
    }

    render() {
        console.log(this.props.stripe);
        return (
            <div className="col-8">
                <p>決済情報の入力</p>
                <Formik
                    initialValues={{ amount: 100, username: 'TARO YAMADA' }}
                    onSubmit={(values) => this.handlePayment(values)}
                    validationSchema={Yup.object().shape({
                        amount: Yup.number().min(1).max(1000),
                    })}
                >
                    {
                        ({ handleChange, handleSubmit, handleBlur, values, errors, touched }) => (
                            <Form onSubmit={handleSubmit}>
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
                                    <FormFeedback>
                                        {errors.amount}
                                    </FormFeedback>
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
                                    <FormFeedback>
                                        {errors.username}
                                    </FormFeedback>
                                </FormGroup>
                                {/* <CardElement
                                    className="bg-light p-3"
                                    hidePostalCode={true}
                                /> */}
                                <legend className="col-form-label">カード番号</legend>
                                <CardNumberElement
                                    ref={this.cardNumberRef}
                                    className="p-2 bg-light"
                                />
                                <legend className="col-form-label">有効期限</legend>
                                <CardExpiryElement
                                    className="p-2 bg-light"
                                />
                                <legend className="col-form-label">セキュリティーコード</legend>
                                <CardCVCElement
                                    className="p-2 bg-light"
                                />

                                <Button
                                    onClick={this.submit}
                                    className="my-3"
                                    color="primary"
                                >
                                    購入
                                </Button>
                            </Form>
                        )
                    }
                </Formik>

            </div>
        );
    }
}

export default injectStripe(CheckoutForm);
