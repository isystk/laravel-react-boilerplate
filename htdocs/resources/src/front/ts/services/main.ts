import ShopService from "@/services/shop";
import AuthService from "@/services/auth";
import LikeService from "@/services/like";
import CartService from "@/services/cart";

export default class MainService {
    _setAppRoot: (appRoot: MainService) => void;
    isShowLoading: boolean;
    auth: AuthService;
    shop: ShopService;
    cart: CartService;
    like: LikeService;

    constructor(setAppRoot: (appRoot: MainService) => void) {
        this._setAppRoot = setAppRoot;
        this.isShowLoading = false;
        this.auth = new AuthService(this);
        this.shop = new ShopService(this);
        this.cart = new CartService(this);
        this.like = new LikeService(this);
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
