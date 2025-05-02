import ShopState from '@/stores/state/shop';
import AuthState from '@/stores/state/auth';
import ConstState from '@/stores/state/const';
import LikeState from '@/stores/state/like';
import CartState from '@/stores/state/cart';

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
