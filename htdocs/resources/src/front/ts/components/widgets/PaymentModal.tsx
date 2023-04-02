import React, { FC } from "react";
import { Button, FormGroup, Label, FormFeedback } from "reactstrap";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import { Url } from "@/constants/url";
import CSRFToken from "@/components/elements/CSRFToken";
import MainService from "@/services/main";
import Modal from "@/components/widgets/Modal";
import { useNavigate } from "react-router-dom";
import {
    useStripe,
    useElements,
    CardNumberElement,
    CardExpiryElement,
    CardCvcElement,
} from "@stripe/react-stripe-js";

type Props = {
    isOpen: boolean;
    handleClose: () => void;
    appRoot: MainService;
    amount: number;
};

const PaymentModal: FC<Props> = ({ isOpen, handleClose, appRoot, amount }) => {
    const navigate = useNavigate();
    const stripe = useStripe();
    const elements = useElements();
    const handlePayment = async (values) => {
        console.log(values);

        if (!stripe || !elements) {
            // Stripe.js has not yet loaded.
            // Make sure to disable form submission until Stripe.js has loaded.
            return;
        }

        // 決算処理を行う
        const result = await appRoot.cart.payment(stripe, elements, values);
        if (result) {
            // 完了画面を表示する
            navigate(Url.SHOP_COMPLETE);
        }
    };

    return (
        <Modal isOpen={isOpen} handleClose={handleClose}>
            <div style={{ padding: "20px" }}>
                <h2
                    style={{
                        fontSize: "16px",
                        textAlign: "center",
                        fontWeight: "bold",
                    }}
                >
                    決済情報の入力
                </h2>
                <Formik
                    initialValues={{
                        amount: amount,
                        username: appRoot.auth.email || "",
                    }}
                    onSubmit={handlePayment}
                    validationSchema={Yup.object().shape({
                        amount: Yup.number()
                            .min(0, "金額は0以上で入力してください")
                            .required("金額を入力してください"),
                        username: Yup.string()
                            .email("メールアドレスを正しく入力してしてください")
                            .required("メールアドレスを入力してください"),
                    })}
                >
                    {({ values, errors }) => (
                        <Form>
                            <CSRFToken appRoot={appRoot} />
                            <FormGroup>
                                <Label>金額</Label>
                                <p>{values.amount}円</p>
                                <FormFeedback>{errors.amount}</FormFeedback>
                            </FormGroup>
                            <FormGroup>
                                <Label>メールアドレス</Label>
                                <p>{values.username}</p>
                                <FormFeedback>{errors.username}</FormFeedback>
                            </FormGroup>
                            <legend className="col-form-label">
                                カード番号
                            </legend>
                            <CardNumberElement className="p-2 bg-light" />
                            <legend className="col-form-label">有効期限</legend>
                            <CardExpiryElement className="p-2 bg-light" />
                            <legend className="col-form-label">
                                セキュリティーコード
                            </legend>
                            <CardCvcElement className="p-2 bg-light" />
                            <p className="text-center">
                                <Button
                                    type="submit"
                                    className="my-3"
                                    color="primary"
                                >
                                    購入する
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
        </Modal>
    );
};

export default PaymentModal;
