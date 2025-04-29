import styles from './styles.module.scss';
import { Formik, Form } from "formik";
import * as Yup from "yup";
import { Url } from "@/constants/url";
import CSRFToken from "@/components/atoms/CSRFToken";
import MainService from "@/services/main";
import { useNavigate } from "react-router-dom";
import {
    useStripe,
    useElements,
    CardNumberElement,
    CardExpiryElement,
    CardCvcElement,
} from "@stripe/react-stripe-js";
import Modal from "@/components/atoms/Modal";

type Props = {
    isOpen: boolean;
    handleClose: () => void;
    appRoot: MainService;
    amount: number;
};

const PaymentModal = ({ isOpen, handleClose, appRoot, amount }: Props) => {
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
        <Modal isOpen={isOpen} onClose={handleClose}>
            <h2 className="text-center font-bold text-xl mb-4">決済情報の入力</h2>
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
                        <CSRFToken />
                        <div>
                            <label className="font-bold">金額</label>
                            <p className="p-3">{values.amount}円</p>
                            {errors.amount && (
                                <p className="p-3">{errors.amount}</p>
                            )}
                        </div>
                        <div>
                            <label className="font-bold">メールアドレス</label>
                            <p className="p-3">{values.username}</p>
                            {errors.username && (
                                <p className="p-3">{errors.username}</p>
                            )}
                        </div>
                        <div className="mt-3">
                            <label className="font-bold">カード番号</label>
                            <CardNumberElement className={styles.formControl} />
                        </div>
                        <div className="mt-3">
                            <label className="font-bold">有効期限</label>
                            <CardExpiryElement className={styles.formControl} />
                        </div>
                        <div className="mt-3">
                            <label className="font-bold">セキュリティーコード</label>
                            <CardCvcElement className={styles.formControl} />
                        </div>
                        <p className="text-center mt-3">
                            <button
                                type="submit"
                                className="btn btn-primary"
                                disabled={!stripe}
                            >
                                購入する
                            </button>
                        </p>
                    </Form>
                )}
            </Formik>
            <div className="mx-auto my-5 border w-100 p-3">
                テスト用クレジットカード番号
                <br />
                5555555555554444
            </div>
        </Modal>
    );
};

export default PaymentModal;
