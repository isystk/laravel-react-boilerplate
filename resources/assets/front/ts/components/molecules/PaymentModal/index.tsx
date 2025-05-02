import styles from './styles.module.scss';
import { Formik, Form } from 'formik';
import * as Yup from 'yup';
import { Url } from '@/constants/url';
import CSRFToken from '@/components/atoms/CSRFToken';
import { useNavigate } from 'react-router-dom';
import {
  useStripe,
  useElements,
  CardNumberElement,
  CardExpiryElement,
  CardCvcElement,
} from '@stripe/react-stripe-js';
import Modal from '@/components/interactions/Modal';
import useAppRoot from '@/stores/useAppRoot';

type Props = {
  isOpen: boolean;
  handleClose: () => void;
  amount: number;
};

const PaymentModal = ({ isOpen, handleClose, amount }: Props) => {
  const appRoot = useAppRoot();
  if (!appRoot) return <></>;

  const navigate = useNavigate();
  const stripe = useStripe();
  const elements = useElements();

  const handlePayment = async values => {
    if (!stripe || !elements) {
      return;
    }

    // 決算処理を行う
    const result = await appRoot.cart.payment(stripe, elements, values);
    if (result) {
      // 完了画面を表示する
      navigate(Url.payComplete);
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={handleClose}>
      <>
        <h2 className="text-center font-bold text-xl mb-4">決済情報の入力</h2>
        <Formik
          initialValues={{
            amount: amount,
            username: appRoot.auth.email,
            cardNumber: '',
            cardExp: '',
            cardCvc: '',
          }}
          onSubmit={handlePayment}
          validationSchema={Yup.object().shape({
            cardNumber: Yup.string().required('カード番号を入力してください'),
            cardExp: Yup.string().required('有効期限を入力してください'),
            cardCvc: Yup.string().required('セキュリティコードを入力してください'),
          })}
        >
          {({ isValid, values, errors, setFieldValue }) => (
            <Form>
              <CSRFToken />
              <div>
                <label className="font-bold">金額</label>
                <p className="p-3">{values.amount}円</p>
              </div>
              <div>
                <label className="font-bold">メールアドレス</label>
                <p className="p-3">{values.username}</p>
              </div>
              <div className="mt-3">
                <label className="font-bold">
                  カード番号{' '}
                  <span className="text-red-600 text-sm">(テストデータ：5555555555554444)</span>
                </label>
                <CardNumberElement
                  className={styles.formControl}
                  onChange={event => {
                    // Stripe Elements はセキュリティ上の理由から値を直接 JavaScript 経由で取得できない」仕様になっている
                    setFieldValue('cardNumber', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardNumber && <p className={styles.error}>{errors.cardNumber}</p>}
              </div>
              <div className="mt-3">
                <label className="font-bold">有効期限</label>
                <CardExpiryElement
                  className={styles.formControl}
                  onChange={event => {
                    // Stripe Elements はセキュリティ上の理由から値を直接 JavaScript 経由で取得できない」仕様になっている
                    setFieldValue('cardExp', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardExp && <p className={styles.error}>{errors.cardExp}</p>}
              </div>
              <div className="mt-3">
                <label className="font-bold">セキュリティーコード</label>
                <CardCvcElement
                  className={styles.formControl}
                  onChange={event => {
                    // Stripe Elements はセキュリティ上の理由から値を直接 JavaScript 経由で取得できない」仕様になっている
                    setFieldValue('cardCvc', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardCvc && <p className={styles.error}>{errors.cardCvc}</p>}
              </div>
              <p className="text-center mt-3">
                <button type="submit" className="btn btn-primary" disabled={!stripe || !isValid}>
                  購入する
                </button>
              </p>
            </Form>
          )}
        </Formik>
      </>
    </Modal>
  );
};

export default PaymentModal;
