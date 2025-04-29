import Env from "@/constants/env";

const getBffUrl = (path: string): string => {
    return [Env.endpointUrl, path].join("");
};

/** API のエンドポイント */
export const Api = {
    /** ログイン状態チェック */
    loginCheck: getBffUrl("/loginCheck"),
    /** ログイン */
    login: getBffUrl("/authenticate"),
    /** ログアウト */
    logout: getBffUrl("/logout"),
    /** 共通定数 */
    consts: getBffUrl("/consts"),

    /** お気に入りデータ取得 */
    likes: getBffUrl("/likes"),
    /** お気に入り追加 */
    likesStore: getBffUrl("/likes/store"),
    /** お気に入り削除 */
    likesDestroy: getBffUrl("/likes/destroy"),

    /** お問い合わせ登録 */
    contactStore: getBffUrl("/contact/store"),

    /** 商品一覧データ取得 */
    shops: getBffUrl("/shops"),
    /** マイカートデータ取得 */
    myCarts: getBffUrl("/mycart"),
    /** カートに商品を追加する */
    addMyCarts: getBffUrl("/addMycart"),
    /** カートから商品を削除する */
    removeMyCart: getBffUrl("/cartdelete"),
    /** Stripe用のペイメント作成 */
    createPayment: getBffUrl("/createPayment"),
    /** 決算処理後の後処理 */
    checkout: getBffUrl("/checkout"),
};
