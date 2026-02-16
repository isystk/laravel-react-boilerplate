import StockService from '@/services/stock';
import AuthService from '@/services/auth';
import ConstService from '@/services/const';
import LikeService from '@/services/like';
import CartService from '@/services/cart';
import ContactService from '@/services/contact';
import ProfileService from '@/services/profile';
import RootState from '@/states/root';

export default class MainService {
  public readonly root: RootState;
  private readonly _setRootState: (root: RootState) => void;
  public auth: AuthService;
  public const: ConstService;
  public stock: StockService;
  public cart: CartService;
  public like: LikeService;
  public contact: ContactService;
  public profile: ProfileService;

  constructor(root: RootState, setRootState: (root: RootState) => void) {
    this.root = root;
    this._setRootState = setRootState;
    this.auth = new AuthService(this);
    this.const = new ConstService(this);
    this.stock = new StockService(this);
    this.cart = new CartService(this);
    this.like = new LikeService(this);
    this.contact = new ContactService(this);
    this.profile = new ProfileService(this);
  }

  public setRootState() {
    this._setRootState(this.root);
  }

  public showLoading() {
    this.root.isLoading = true;
    this.setRootState();
  }
  public hideLoading() {
    this.root.isLoading = false;
    this.setRootState();
  }

  public showToastMessage(message: string) {
    this.root.toastMessage = message;
    this.setRootState();
  }
  public hideToastMessage() {
    this.root.toastMessage = null;
    this.setRootState();
  }
}
