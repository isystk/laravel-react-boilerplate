import ShopService from '@/services/shop';
import AuthService from '@/services/auth';
import ConstService from '@/services/const';
import LikeService from '@/services/like';
import CartService from '@/services/cart';
import ContactService from '@/services/contact';
import RootState from '@/states/root';

export default class MainService {
  public readonly root: RootState;
  private readonly _setRootState: (root: RootState) => void;
  public auth: AuthService;
  public const: ConstService;
  public shop: ShopService;
  public cart: CartService;
  public like: LikeService;
  public contact: ContactService;

  constructor(root: RootState | null, setRootState: (root: RootState) => void) {
    this.root = root || new RootState();
    this._setRootState = setRootState;
    this.auth = new AuthService(this);
    this.const = new ConstService(this);
    this.shop = new ShopService(this);
    this.cart = new CartService(this);
    this.like = new LikeService(this);
    this.contact = new ContactService(this);
  }

  public setRootState() {
    this._setRootState(this.root);
  }

  public showLoading() {
    this.root.isShowLoading = true;
    this.setRootState();
  }
  public hideLoading() {
    this.root.isShowLoading = false;
    this.setRootState();
  }

  public showToastMessage(message) {
    this.root.toastMessage = message;
    this.setRootState();
  }
  public hideToastMessage() {
    this.root.toastMessage = null;
    this.setRootState();
  }
}
