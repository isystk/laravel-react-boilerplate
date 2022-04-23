import React, { VFC } from "react";
import {
    CardNumberElement,
    CardExpiryElement,
    CardCVCElement,
    injectStripe
} from "react-stripe-elements";
import {
    Button,
    Form,
    FormGroup,
    Label,
    Input,
    FormFeedback
} from "reactstrap";
import { Formik } from "formik";
import * as Yup from "yup";
import { API_ENDPOINT } from "@/constants/api";
import { Url } from "@/constants/url";
import CSRFToken from "@/components/Elements/CSRFToken";
import { API } from "@/utilities/api";
import { useDispatch } from "react-redux";
import { hideLoading, showLoading } from "@/services/actions";
import { push } from "connected-react-router";

type Props = {
    stripe;
    elements;
    username: string;
    amount: number;
};

const CheckoutForm: VFC<Props> = props => {
    const dispatch = useDispatch();
    const handlePayment = async values => {
        // alert(JSON.stringify(values));

        // ローディングを表示する
        dispatch(showLoading());

        //paymentIntentの作成を（ローカルサーバ経由で）リクエスト
        const response = await API.post(API_ENDPOINT.CREATE_PAYMENT, {
            amount: values.amount,
            username: values.username
        });

        //レスポンスからclient_secretを取得
        const client_secret = response.client_secret;

        //client_secretを利用して（確認情報をStripeに投げて）決済を完了させる
        const confirmRes = await props.stripe.confirmCardPayment(
            client_secret,
            {
                payment_method: {
                    // card: this.props.elements.getElement('card'),
                    card: props.elements.getElement("cardNumber"),
                    billing_details: {
                        name: values.username
                    }
                }
            }
        );

        if (confirmRes.paymentIntent.status === "succeeded") {
            // 決算処理が完了したら、注文履歴に追加してマイカートから商品を削除する。
            const response = await API.post(API_ENDPOINT.CHECKOUT, {});

            if (response.result) {
                // 完了画面を表示する
                dispatch(push(Url.SHOP_COMPLETE));
            }
        }

        // ローディングを非表示にする
        dispatch(hideLoading());
    };

    return (
        <div style={{ padding: "20px" }}>
            <h2
                style={{
                    fontSize: "16px",
                    textAlign: "center",
                    fontWeight: "bold"
                }}
            >
                決済情報の入力
            </h2>
            <Formik
                initialValues={{
                    amount: props.amount,
                    username: props.username
                }}
                onSubmit={values => handlePayment(values)}
                validationSchema={Yup.object().shape({
                    amount: Yup.number()
                        .min(0, "金額は0以上で入力してください")
                        .required("金額を入力してください"),
                    username: Yup.string()
                        .email("メールアドレスを正しく入力してしてください")
                        .required("メールアドレスを入力してください")
                })}
            >
                {({ handleSubmit, values, errors, touched }) => (
                    <Form onSubmit={handleSubmit}>
                        <CSRFToken />
                        <FormGroup>
                            <Label>金額</Label>
                            <p>{values.amount}円</p>
                            <FormFeedback>{errors.amount}</FormFeedback>
                        </FormGroup>
                        <FormGroup>
                            <Label>メールアドレス</Label>
                            <p>{values.username}</p>
                            <Input
                                type="hidden"
                                name="username"
                                value={values.username}
                                invalid={Boolean(
                                    touched.username && errors.username
                                )}
                            />
                            <FormFeedback>{errors.username}</FormFeedback>
                        </FormGroup>
                        <legend className="col-form-label">カード番号</legend>
                        <CardNumberElement className="p-2 bg-light" />
                        <legend className="col-form-label">有効期限</legend>
                        <CardExpiryElement className="p-2 bg-light" />
                        <legend className="col-form-label">
                            セキュリティーコード
                        </legend>
                        <CardCVCElement className="p-2 bg-light" />
                        <p className="text-center">
                            <Button
                                type="submit"
                                className="my-3"
                                color="primary"
                            >
                                購入
                            </Button>
                        </p>
                    </Form>
                )}
            </Formik>
            <p className="fz-s">
                テスト用クレジットカード番号
                <br />
                5555555555554444
            </p>
        </div>
    );
};

export default injectStripe(CheckoutForm);
