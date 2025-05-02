import ShopState from '@/state/shop';
import AuthState from '@/state/auth';
import ConstState from '@/state/const';
import LikeState from '@/state/like';
import CartState from '@/state/cart';

export default class RootState {
  public isShowLoading: boolean;
  public auth: AuthState;
  public const: ConstState;
  public shop: ShopState;
  public cart: CartState;
  public like: LikeState;

  constructor() {
    this.isShowLoading = false;
    this.auth = new AuthState();
    this.const = new ConstState();
    this.shop = new ShopState();
    this.cart = new CartState();
    this.like = new LikeState();
  }
}
