import AuthState from '@/states/auth';
import ConstState from '@/states/const';
import LikeState from '@/states/like';
import CartState from '@/states/cart';

export default class RootState {
  public isLoading: boolean;
  public toastMessage: string | null;
  public auth: AuthState;
  public const: ConstState;
  public cart: CartState;
  public like: LikeState;

  constructor() {
    this.isLoading = false;
    this.toastMessage = null;
    this.auth = new AuthState();
    this.const = new ConstState();
    this.cart = new CartState();
    this.like = new LikeState();
  }
}
