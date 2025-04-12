import ShopService from "@/services/shop";
import AuthService from "@/services/auth";
import ConstService from "@/services/const";
import LikeService from "@/services/like";
import CartService from "@/services/cart";
import ContactService from "@/services/contact";

export default class MainService {
    _setAppRoot: (appRoot: MainService) => void;
    isShowLoading: boolean;
    auth: AuthService;
    const: ConstService;
    shop: ShopService;
    cart: CartService;
    like: LikeService;
    contact: ContactService;

    constructor(setAppRoot: (appRoot: MainService) => void) {
        this._setAppRoot = setAppRoot;
        this.isShowLoading = false;
        this.auth = new AuthService(this);
        this.const = new ConstService(this);
        this.shop = new ShopService(this);
        this.cart = new CartService(this);
        this.like = new LikeService(this);
        this.contact = new ContactService(this);
    }

    setAppRoot() {
        this._setAppRoot(this);
    }

    showLoading() {
        this.isShowLoading = true;
        this.setAppRoot();
    }
    hideLoading() {
        this.isShowLoading = false;
        this.setAppRoot();
    }
}
