import ShopState from '@/states/shop';
import AuthState from '@/states/auth';
import ConstState from '@/states/const';
import LikeState from '@/states/like';
import CartState from '@/states/cart';

export default class RootState {
  public isShowLoading: boolean;
  public toastMessage: string | null;
  public auth: AuthState;
  public const: ConstState;
  public shop: ShopState;
  public cart: CartState;
  public like: LikeState;

  constructor() {
    this.isShowLoading = false;
    this.toastMessage = null;
    this.auth = new AuthState();
    this.const = new ConstState();
    this.shop = new ShopState();
    this.cart = new CartState();
    this.like = new LikeState();
  }
}
