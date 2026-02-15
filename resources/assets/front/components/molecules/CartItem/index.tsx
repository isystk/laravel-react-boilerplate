import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import useAppRoot from '@/states/useAppRoot';
import { useState } from 'react';
import { ToastMessage, ToastTypes } from '@/components/interactions/ToastMessage';

export type Props = {
  id: number;
  name: string;
  detail: string;
  price: number;
  imageUrl: string;
};

const CartItem = ({ id, name, imageUrl, price, detail }: Props) => {
  const { state, service } = useAppRoot();

  const [isShowDeleteConfirm, setIsShowDeleteConfirm] = useState(false);

  if (!state) return <></>;
  const handleDeleteFromCart = async () => {
    await service.cart.removeCart(id);
    await service.cart.readCarts();
  };

  return (
    <div className={styles.cardItem}>
      <Image src={imageUrl} width={276} height={184} alt={name} className="mb-2 w-100" />
      <p className="font-bold">{name}</p>
      <p className="text-red-600">{price}</p>
      <p>{detail}</p>
      <button
        className="btn btn-sm text-center mx-auto mt-auto btn-danger"
        onClick={() => {
          setIsShowDeleteConfirm(true);
        }}
      >
        カートから削除する
      </button>
      <ToastMessage
        isOpen={isShowDeleteConfirm}
        message="削除します。よろしいですか？"
        type={ToastTypes.Confirm}
        onConfirm={() => {
          setIsShowDeleteConfirm(false);
          handleDeleteFromCart();
        }}
        onCancel={() => {
          setIsShowDeleteConfirm(false);
        }}
      />
    </div>
  );
};

export default CartItem;
