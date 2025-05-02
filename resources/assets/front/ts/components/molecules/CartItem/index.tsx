import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import useAppRoot from '@/stores/useAppRoot';
import { type Cart } from '@/services/cart';

export type Props = Cart & {
  key: number | string;
};

const CartItem = ({ id, name, imgpath, price, detail }: Props) => {
  const [state, service] = useAppRoot();
  if (!state) return <></>;

  const handleDeleteFromCart = async () => {
    await service.cart.removeCart(id);
    await service.cart.readCarts();
  };

  return (
    <div className={styles.cardItem}>
      <Image
        src={`/uploads/stock/${imgpath}`}
        width={276}
        height={184}
        alt={name}
        className="mb-2 w-100"
      />
      <p className="font-bold">{name}</p>
      <p className="text-red-600">{price}</p>
      <p>{detail}</p>
      <button
        className="btn btn-sm text-center mx-auto mt-auto btn-danger"
        onClick={handleDeleteFromCart}
      >
        カートから削除する
      </button>
    </div>
  );
};

export default CartItem;
