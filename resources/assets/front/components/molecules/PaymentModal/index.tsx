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
import useAppRoot from '@/states/useAppRoot';
import { useTranslation } from 'react-i18next';

type FormValues = {
  amount: number;
  email: string;
  cardNumber: string;
  cardExp: string;
  cardCvc: string;
};

type Props = {
  isOpen: boolean;
  handleClose: () => void;
  amount: number;
};

const PaymentModal = ({ isOpen, handleClose, amount }: Props) => {
  const { state, service } = useAppRoot();
  const { t } = useTranslation(['cart', 'validation']);

  const navigate = useNavigate();
  const stripe = useStripe();
  const elements = useElements();

  if (!state) return <></>;
  const handlePayment = async (values: FormValues) => {
    const { amount, email } = values;
    if (!stripe || !elements) {
      return;
    }

    await service.cart.payment({ stripe, elements, amount, email });
    navigate(Url.PAY_COMPLETE);
  };

  const initialValues = {
    amount: amount,
    email: state.auth.email,
    cardNumber: '',
    cardExp: '',
    cardCvc: '',
  } as FormValues;

  return (
    <Modal isOpen={isOpen} onClose={handleClose}>
      <>
        <h2 className="text-center font-bold text-xl mb-4">{t('cart:payment.title')}</h2>
        <Formik
          initialValues={initialValues}
          onSubmit={handlePayment}
          validationSchema={Yup.object().shape({
            cardNumber: Yup.string().required(t('validation:cardNumber')),
            cardExp: Yup.string().required(t('validation:cardExp')),
            cardCvc: Yup.string().required(t('validation:cardCvc')),
          })}
        >
          {({ isValid, values, errors, setFieldValue }) => (
            <Form>
              <CSRFToken />
              <div>
                <label className="font-bold">{t('cart:payment.amount')}</label>
                <p className="p-3">{t('cart:payment.amountValue', { amount: values.amount })}</p>
              </div>
              <div>
                <label className="font-bold">{t('cart:payment.email')}</label>
                <p className="p-3">{values.email}</p>
              </div>
              <div className="mt-3">
                <label className="font-bold">
                  {t('cart:payment.cardNumber')}{' '}
                  <span className="text-red-600 text-sm">{t('cart:payment.cardNumberTest')}</span>
                </label>
                <CardNumberElement
                  className={styles.formControl}
                  onChange={event => {
                    setFieldValue('cardNumber', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardNumber && <p className={styles.error}>{errors.cardNumber}</p>}
              </div>
              <div className="mt-3">
                <label className="font-bold">{t('cart:payment.cardExp')}</label>
                <CardExpiryElement
                  className={styles.formControl}
                  onChange={event => {
                    setFieldValue('cardExp', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardExp && <p className={styles.error}>{errors.cardExp}</p>}
              </div>
              <div className="mt-3">
                <label className="font-bold">{t('cart:payment.cardCvc')}</label>
                <CardCvcElement
                  className={styles.formControl}
                  onChange={event => {
                    setFieldValue('cardCvc', event.complete ? 'complete' : '');
                  }}
                />
                {errors.cardCvc && <p className={styles.error}>{errors.cardCvc}</p>}
              </div>
              <p className="text-center mt-3">
                <button type="submit" className="btn btn-primary" disabled={!stripe || !isValid}>
                  {t('cart:payment.submit')}
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
